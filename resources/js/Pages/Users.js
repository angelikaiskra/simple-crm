import React, {useState, useEffect, useRef} from 'react';
import Header from "../Layout/Header";
import {Button} from "primereact/button";
import axios from "axios";
import {Toolbar} from "primereact/primereact.all.esm";
import {DataTable} from "primereact/datatable";
import {Column} from "primereact/column";
import {Paginator} from "primereact/paginator";
import {Dialog} from "primereact/dialog";
import {InputText} from "primereact/inputtext";
import {showError, showMessage, showSuccess} from "../Helpers/helpers";
import {Dropdown} from "primereact/dropdown";

const Users = (props) => {

    const [users, setUsers] = useState([]);
    const [selectedUser, setSelectedUser] = useState({});

    const dt = useRef(null);
    const [userDialog, setUserDialog] = useState(false);

    const [first, setFirst] = useState(1);
    const [rows, setRows] = useState(20);
    const [totalRecords, setTotalRecords] = useState(0);
    const [currentPage, setCurrentPage] = useState(1);

    const currentUserRole = JSON.parse(localStorage.getItem("user")).role;

    const fetchUsers = (page = currentPage, limit = rows) => {
        axios.get(`/users?limit=${limit}&page=${page}`, {
            'Authorization': `Token ${localStorage.getItem("token")}`
        }).then((res) => {
            console.log(res.data);
            setUsers(res.data.data);
            setTotalRecords(res.data.meta.total);
        })
            .catch((e) => {
                console.log(e.response);
                showError(e.response);
            })
    }

    useEffect(() => {
        fetchUsers();
    }, []);

    const hideDialog = () => {
        setUserDialog(false);
    }

    const userRoles = [
        {label: 'Normal user', value: 'user'},
        {label: 'Moderator', value: 'moderator'},
        {label: 'Admin', value: 'admin'},
    ];

    const saveUser = () => {
        console.log("save user", selectedUser)

        if (selectedUser.new) {
            axios.post('/register', {
                "name": selectedUser.name,
                "surname": selectedUser.surname,
                "login": selectedUser.login,
                "date_of_birth": selectedUser.date_of_birth,
                "password": selectedUser.password,
                "password_confirmation": selectedUser.password_confirmation,
            }, {
                'Authorization': `Token ${localStorage.getItem("token")}`
            })
                .then((res) => {
                    console.log(res);
                    showMessage("Pomyślnie dodano nowego użytkownika");
                    fetchUsers();
                    hideDialog();
                    setSelectedUser({});
                })
                .catch((e) => {
                    console.log(e.response);
                    showError(e.response);
                })
        } else {
            axios.put('/user/' + selectedUser.id, {
                "name": selectedUser.name,
                "surname": selectedUser.surname,
                "login": selectedUser.login,
                "date_of_birth": selectedUser.date_of_birth,
                ...(selectedUser.role && {"role": selectedUser.role})
            }, {
                'Authorization': `Token ${localStorage.getItem("token")}`
            })
                .then((res) => {
                    console.log(res);
                    showMessage("Pomyślnie zedytowano użytkownika");
                    fetchUsers();
                    hideDialog();
                    setSelectedUser({});
                })
                .catch((e) => {
                    console.log(e.response);
                    showError(e.response);
                })
        }
    }

    const editUser = (user) => {
        setSelectedUser({...user});
        setUserDialog(true);
    }

    const addUser = () => {
        setSelectedUser({
            "new": true,
            "name": "",
            "surname": "",
            "date_of_birth": "",
            "login": "",
            "password": "",
            "password_confirmation": ""
        });

        setUserDialog(true);
    }

    const deleteUser = (user) => {
        console.log("delete user", user)
        axios.delete('/user/' + user.id, {
            'Authorization': `Token ${localStorage.getItem("token")}`
        })
            .then((res) => {
                console.log(res);
                fetchUsers();
                showSuccess(res);
            })
            .catch((e) => {
                console.log(e.response);
                showError(e.response);
            })
    }

    const onInputChange = (e, name) => {
        const val = (e.target && e.target.value) || '';
        let _user = {...selectedUser};
        _user[`${name}`] = val;

        setSelectedUser(_user);
    }

    const onPageChange = (event) => {
        setRows(event.rows);
        setFirst(event.first);
        setCurrentPage(event.page + 1);
        fetchUsers(event.page + 1, event.rows);
    }

    const toolbarTemplate = () => {
        return (
            <Button label="New" icon="pi pi-plus" className="p-button-success" onClick={() => addUser()}/>
        )
    }

    const header = (
        <div className="table-header">
            <h5 className="mx-0 my-1">List of users</h5>
        </div>
    );

    const actionBodyTemplate = (rowData) => {
        return (
            <React.Fragment>
                <Button icon="pi pi-pencil" className="p-button-rounded p-button-success mr-2"
                        onClick={() => editUser(rowData)}/>
                { currentUserRole === 'admin' &&
                <Button icon="pi pi-trash" className="p-button-rounded p-button-warning"
                        onClick={() => deleteUser(rowData)}/> }
            </React.Fragment>
        );
    }

    const productDialogFooter = (
        <React.Fragment>
            <Button label="Cancel" icon="pi pi-times" className="p-button-text" onClick={hideDialog}/>
            <Button label="Save" icon="pi pi-check" className="p-button-text" onClick={saveUser}/>
        </React.Fragment>
    );

    return (
        <>
            <Header history={props.history}/>
            <div className={"content"}>
                <div className="card">
                    {(currentUserRole === 'admin' || currentUserRole === 'moderator') && <Toolbar className="mb-4" left={toolbarTemplate}/> }

                    <DataTable ref={dt} value={users}
                               dataKey="id"
                               header={header} responsiveLayout="scroll">
                        <Column headerStyle={{width: '3rem'}}/>
                        <Column field="id" header="ID"/>
                        <Column field="name" header="Name"/>
                        <Column field="surname" header="Surname"/>
                        <Column field="login" header="Login"/>
                        <Column field="date_of_birth" header="Date of birth"/>
                        {currentUserRole === 'admin' && <Column field="role" header="User role" /> }
                        {(currentUserRole === 'moderator' || currentUserRole === 'admin') && <Column body={actionBodyTemplate}/> }
                    </DataTable>

                    <Paginator first={first} rows={rows} totalRecords={totalRecords} rowsPerPageOptions={[20, 30, 40, 50, 100]}
                               onPageChange={onPageChange} />
                </div>

                <Dialog visible={userDialog} style={{width: '450px'}}
                        header={`User ${selectedUser.id ? selectedUser.id : ''}`} modal
                        className="p-fluid" footer={productDialogFooter} onHide={hideDialog}>
                    <div className="p-field">
                        <label htmlFor="name">Name</label>
                        <InputText id="name" defaultValue={selectedUser.name} onChange={(e) => onInputChange(e, 'name')}/>
                    </div>
                    <div className="p-field mt-4">
                        <label htmlFor="surname">Surname</label>
                        <InputText id="surname" defaultValue={selectedUser.surname}
                                   onChange={(e) => onInputChange(e, 'surname')}/>
                    </div>
                    <div className="p-field mt-4">
                        <label htmlFor="login">Login</label>
                        <InputText id="login" defaultValue={selectedUser.login} onChange={(e) => onInputChange(e, 'login')}/>
                    </div>
                    <div className="p-field mt-4">
                        <label htmlFor="dateOfBirth">Date of birth</label>
                        <InputText id="dateOfBirth" defaultValue={selectedUser.date_of_birth}
                                   onChange={(e) => onInputChange(e, 'date_of_birth')}/>
                    </div>
                    {typeof selectedUser.password !== "undefined" &&
                    <div className="p-field mt-4">
                        <label htmlFor="password">Password</label>
                        <InputText id="password" defaultValue={selectedUser.password} type={"password"}
                                   onChange={(e) => onInputChange(e, 'password')}/>
                    </div>}
                    {typeof selectedUser.password_confirmation !== "undefined" &&
                    <div className="p-field mt-4">
                        <label htmlFor="password_confirmation">Password confirmation</label>
                        <InputText id="password_confirmation" defaultValue={selectedUser.password_confirmation}
                                   type={"password"}
                                   onChange={(e) => onInputChange(e, 'password_confirmation')}/>
                    </div>}
                    {typeof selectedUser.new === "undefined" && currentUserRole === 'admin' &&
                        <div className="p-field mt-4">
                            <label htmlFor="user_role">User role</label>
                            <Dropdown id={"user_role"} value={selectedUser.role} options={userRoles} onChange={(e) => onInputChange(e, 'role')} placeholder="Select role"/>
                        </div>
                    }
                </Dialog>
            </div>
        </>
    );
};

export default Users;

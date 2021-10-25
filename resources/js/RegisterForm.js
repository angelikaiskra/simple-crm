import React, {useRef, useState} from 'react';
import {Link, useHistory} from "react-router-dom";

import {InputText} from 'primereact/inputtext';
import {Calendar} from 'primereact/calendar';
import {Button} from "primereact/button";
import {Toast} from "primereact/toast";

const RegisterForm = (props) => {

    const [name, setName] = useState('');
    const [surname, setSurname] = useState('');
    const [login, setLogin] = useState('');
    const [dateOfBirth, setDateOfBirth] = useState('');
    const [password, setPassword] = useState('');
    const [confirmPassword, setConfirmPassword] = useState('');

    const toast = useRef();
    const history = useHistory();

    const onRegisterClicked = (e) => {
        e.preventDefault();

        const date = new Date(dateOfBirth)

        API.post('/register', {
            "name": name,
            "surname": surname,
            "date_of_birth": `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()}`,
            "login": login,
            "password": password,
            "password_confirmation": confirmPassword
        }).then((res) => {
            toast.current.show({severity: 'success', summary: "Success", detail: res.data.message, life: 3000})
            history.push('/login')

        }).catch((e) => {
            Object.keys(e.response.data.errors).map(function (key, index) {
                toast.current.show({
                    severity: 'error',
                    summary: e.response.data.message,
                    detail: e.response.data.errors[key],
                    life: 3000
                })
            });
        })
    }

    return (
        <>
            <Toast ref={toast}/>
            <div className={"formWrapper"}>
                <form className={"form"}>
                    <div className={"form__header"}>
                        <span>Simple<b>CRM</b></span>
                    </div>

                    <div className={"form__content"}>
                        <p>Create your new user account</p>

                        <div className="p-inputgroup">
                            <InputText value={name} onChange={(e) => setName(e.target.value)} placeholder={"Name"}
                                       required/>
                        </div>

                        <div className="p-inputgroup">
                            <InputText value={surname} onChange={(e) => setSurname(e.target.value)}
                                       placeholder={"Surname"} required/>
                        </div>

                        <div className="p-inputgroup">
                            <InputText value={login} onChange={(e) => setLogin(e.target.value)} placeholder={"Login"}
                                       required/>
                        </div>

                        <div className="p-inputgroup">
                            <Calendar value={dateOfBirth} onChange={(e) => setDateOfBirth(e.value)}
                                      placeholder={"Date of birth"} dateFormat="yy-mm-dd" required/>
                        </div>

                        <div className="p-inputgroup">
                            <InputText value={password} onChange={(e) => setPassword(e.target.value)}
                                       placeholder={"Password"} type={"password"} required/>
                        </div>

                        <div className="p-inputgroup">
                            <InputText value={confirmPassword} onChange={(e) => setConfirmPassword(e.target.value)}
                                       placeholder={"Confirm password"} type={"password"} required/>
                        </div>

                        <Button label={"Sign up"} onClick={(e) => onRegisterClicked(e)}/>
                    </div>

                    <div className={"form__footer"}>
                        <span>Already have an account? </span><Link to="/login">Login</Link>
                    </div>
                </form>
            </div>
        </>
    );
};

export default RegisterForm;

import React, {useRef, useState} from 'react';
import {Link, useHistory} from "react-router-dom";

import {InputText} from 'primereact/inputtext';
import {Button} from "primereact/button";
import {Toast} from "primereact/toast";

const LoginForm = () => {

    const [login, setLogin] = useState('');
    const [password, setPassword] = useState('');

    const toast = useRef();
    const history = useHistory();

    const onLoginClicked = (e) => {
        e.preventDefault();

        API.post('/login', {
            "login": login,
            "password": password,
        }).then((res) => {
            toast.current.show({severity: 'success', summary: "Success", detail: res.data.message, life: 3000});
            localStorage.setItem('token', res.data.data.token);
            localStorage.setItem('user', res.data.data.user.toString());
            history.push('/users');

        }).catch((e) => {
            toast.current.show({
                severity: 'error',
                summary: "Error",
                detail: e.response.data.message,
                life: 3000
            })
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
                        <p>Sign in to start your session</p>

                        <div className="p-inputgroup">
                            <InputText value={login} onChange={(e) => setLogin(e.target.value)} placeholder={"Login"}
                                       required/>
                            <span className="p-inputgroup-addon">
                            <i className="pi pi-envelope"/>
                        </span>
                        </div>

                        <div className="p-inputgroup">
                            <InputText value={password} onChange={(e) => setPassword(e.target.value)}
                                       placeholder={"Password"} type={"password"}/>
                            <span className="p-inputgroup-addon">
                            <i className="pi pi-lock"/>
                        </span>
                        </div>

                        <Button label={"Sign in"} onClick={(e) => onLoginClicked(e)}/>
                    </div>

                    <div className={"form__footer"}>
                        <span>Don’t have an account? </span><Link to="/register">Register</Link>
                    </div>
                </form>
            </div>
        </>
    );
};

export default LoginForm;

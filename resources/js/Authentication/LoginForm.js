import React, {useState, useEffect} from 'react';
import {Link, Redirect} from "react-router-dom";

import {InputText} from 'primereact/inputtext';
import {Button} from "primereact/button";
import {showError, showMessage} from "../Helpers/helpers";

const LoginForm = (props) => {

    const [login, setLogin] = useState('');
    const [password, setPassword] = useState('');

    useEffect(() => {
        if (localStorage.getItem("token"))
            props.history.push('/users');
    }, []);

    const onLoginClicked = (e) => {
        e.preventDefault();

        API.post('/login', {
            "login": login,
            "password": password,

        }).then((res) => {
            console.log(res.data.data);

            localStorage.setItem("token", res.data.data.token);
            localStorage.setItem("user", JSON.stringify(res.data.data.user))

            showMessage("Pomyślnie zalogowano.")
            props.history.push('/users');

        }).catch((e) => {
            console.log(e.response);
            showError(e.response);
        })
    }

    return (
        <div className={"formWrapper"}>
            <div className={"form"}>
                <div className={"form__header"}>
                    <span>Simple<b>CRM</b></span>
                </div>

                <div className={"form__content"}>
                    <p>Sign in to start your session</p>

                    <div className="p-inputgroup">
                        <InputText value={login} onChange={(e) => setLogin(e.target.value)} placeholder={"Login"} required />
                        <span className="p-inputgroup-addon">
                            <i className="pi pi-user"></i>
                        </span>
                    </div>

                    <div className="p-inputgroup">
                        <InputText value={password} onChange={(e) => setPassword(e.target.value)} placeholder={"Password"} type={"password"} required />
                        <span className="p-inputgroup-addon">
                            <i className="pi pi-lock"></i>
                        </span>
                    </div>

                    <Button label={"Sign in"} onClick={(e) => onLoginClicked(e)} />
                </div>

                <div className={"form__footer"}>
                    <span>Don’t have an account? </span><Link to="/register">Register</Link>
                </div>
            </div>
        </div>
    );
};

export default LoginForm;

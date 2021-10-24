import React, {useState} from 'react';
import {Link} from "react-router-dom";

import {InputText} from 'primereact/inputtext';
import {Button} from "primereact/button";

const LoginForm = () => {

    const [login, setLogin] = useState('');
    const [password, setPassword] = useState('');

    return (
        <div className={"formWrapper"}>
            <div className={"form"}>
                <div className={"form__header"}>
                    <span>Simple<b>CRM</b></span>
                </div>

                <div className={"form__content"}>
                    <p>Sign in to start your session</p>

                    <div className="p-inputgroup">
                        <InputText value={login} onChange={(e) => setLogin(e.target.value)} placeholder={"Login"} />
                        <span className="p-inputgroup-addon">
                            <i className="pi pi-envelope"></i>
                        </span>
                    </div>

                    <div className="p-inputgroup">
                        <InputText value={password} onChange={(e) => setPassword(e.target.value)} placeholder={"Password"} />
                        <span className="p-inputgroup-addon">
                            <i className="pi pi-lock"></i>
                        </span>
                    </div>

                    <Button label={"Sign in"}/>
                </div>

                <div className={"form__footer"}>
                    <span>Don’t have an account? </span><Link to="/register">Register</Link>
                </div>
            </div>
        </div>
    );
};

export default LoginForm;

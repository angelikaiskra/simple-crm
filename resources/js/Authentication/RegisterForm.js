import React, {useState, useEffect} from 'react';
import {Link} from "react-router-dom";

import {InputText} from 'primereact/inputtext';
import {Calendar} from 'primereact/calendar';
import {Button} from "primereact/button";

import {showError, showSuccess} from "../Helpers/helpers";

const RegisterForm = (props) => {

    const [name, setName] = useState('');
    const [surname, setSurname] = useState('');
    const [login, setLogin] = useState('');
    const [dateOfBirth, setDateOfBirth] = useState('');
    const [password, setPassword] = useState('');
    const [confirmPassword, setConfirmPassword] = useState('');

    useEffect(() => {
        if (localStorage.getItem("token"))
            props.history.push('/users');
    }, []);

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
            console.log(res);
            showSuccess(res);
            props.history.push('/login');

        }).catch((e) => {
            console.log(e.response);
            showError(e.response);
        })
    }

    return (
        <div className={"formWrapper"}>
            <form className={"form"}>
                <div className={"form__header"}>
                    <span>Simple<b>CRM</b></span>
                </div>

                <div className={"form__content"}>
                    <p>Create your new user account</p>

                    <div className="p-inputgroup">
                        <InputText value={name} onChange={(e) => setName(e.target.value)} placeholder={"Name"} required />
                    </div>

                    <div className="p-inputgroup">
                        <InputText value={surname} onChange={(e) => setSurname(e.target.value)} placeholder={"Surname"} required />
                    </div>

                    <div className="p-inputgroup">
                        <InputText value={login} onChange={(e) => setLogin(e.target.value)} placeholder={"Login"} required />
                    </div>

                    <div className="p-inputgroup">
                        <Calendar value={dateOfBirth} onChange={(e) => setDateOfBirth(e.value)} placeholder={"Date of birth"} dateFormat="yy-mm-dd" required />
                    </div>

                    <div className="p-inputgroup">
                        <InputText value={password} onChange={(e) => setPassword(e.target.value)} placeholder={"Password"} type={"password"} required />
                    </div>

                    <div className="p-inputgroup">
                        <InputText value={confirmPassword} onChange={(e) => setConfirmPassword(e.target.value)} placeholder={"Confirm password"} type={"password"} required />
                    </div>

                    <Button label={"Sign up"} onClick={(e) => onRegisterClicked(e)} />
                </div>

                <div className={"form__footer"}>
                    <span>Already have an account? </span><Link to="/login">Login</Link>
                </div>
            </form>
        </div>
    );
};

export default RegisterForm;

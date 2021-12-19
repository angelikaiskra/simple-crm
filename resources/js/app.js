import React, {useRef} from 'react';
import ReactDOM from 'react-dom';
import {
    HashRouter as Router,
    Switch,
    Route
} from "react-router-dom";
import axios from './axios';

import LoginForm from "./Authentication/LoginForm";
import RegisterForm from "./Authentication/RegisterForm";
import Users from "./Pages/Users";

import 'bootstrap/dist/css/bootstrap.min.css';

import {Toast} from 'primereact/toast';
import ProtectedRoute from "./Helpers/ProtectedRoute";

function App() {
    const toast = useRef(null);
    window.toast = toast;

    return (
        <>
            <Toast ref={toast}/>
            <Router>
                <Switch>
                    <Route path={"/login"} component={LoginForm} />
                    <Route path={"/register"} component={RegisterForm} />
                    <ProtectedRoute path={"/users"} component={Users} />
                </Switch>
            </Router>
        </>
    );
}

export default App;

if (document.getElementById('app')) {
    ReactDOM.render(<App/>, document.getElementById('app'));
}



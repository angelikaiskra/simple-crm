import React from 'react';
import ReactDOM from 'react-dom';
import {
    HashRouter as Router,
    Switch,
    Route
} from "react-router-dom";
import axios from './axios';

import LoginForm from "./LoginForm";
import RegisterForm from "./RegisterForm";
import Users from "./Users";

function App() {
    return (
        <Router>
            <Switch>
                <Route path={"/login"}>
                    <LoginForm/>
                </Route>
                <Route path={"/register"}>
                    <RegisterForm />
                </Route>
                <Route path={"/"}>
                    <Users />
                </Route>
            </Switch>
        </Router>
    );
}

export default App;

if (document.getElementById('app')) {
    ReactDOM.render(<App/>, document.getElementById('app'));
}



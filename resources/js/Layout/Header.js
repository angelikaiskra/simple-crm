import React, {useState} from 'react';
import {Link} from "react-router-dom";
// import PropTypes from 'prop-types';

const Header = (props) => {

    const user = JSON.parse(localStorage.getItem("user"));

    const logOut = (e) => {
        e.preventDefault();
        localStorage.removeItem("token");
        localStorage.removeItem("user");
        props.history.push('/login');
    }

    return (
        <div className={"Header"}>
            <Link to={"/"} className={"Header__logo"}/>
            <div className={"Header__menu"}>
                <div className={"Header__links"}>
                    <Link to={"/users"}>Users</Link>
                    {/*<Link to={"/companies"}>Companies</Link>*/}
                    {/*<Link to={"/contacts"}>Contacts</Link>*/}
                </div>
                <div className={"Header__logout"}>
                    <span>Hi, {user.name}</span>
                    <a href={""} onClick={(e) => logOut(e)}>
                        <i className="pi pi-sign-out"/>
                    </a>
                </div>
            </div>
        </div>
    );
};

// Header.propTypes = {
//
// };

export default Header;

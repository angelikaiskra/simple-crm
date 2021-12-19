import React from "react";
import { Redirect, Route } from "react-router-dom";

function ProtectedRoute(props) {
    const isAuthenticated = localStorage.getItem("token");
    console.log("isAuthenticated", isAuthenticated);

    return isAuthenticated ? (
        <Route {...props} />
    ) : (
        <Redirect
            to={{pathname: "/login"}}
        />
    );
}

export default ProtectedRoute;

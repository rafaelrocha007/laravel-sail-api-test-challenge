import React from "react";

import {BrowserRouter, Route} from "react-router-dom";

import Login from "./pages/Login";
import Customers from "./pages/Customers";

function Routes() {
    return (
        <BrowserRouter>
            <Route path="/" exact component={Login}/>
            <Route path="/customers" component={Customers}/>
        </BrowserRouter>
    );
}

export default Routes;

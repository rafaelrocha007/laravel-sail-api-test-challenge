import React, {useEffect, useState} from 'react';
import {useHistory} from 'react-router-dom';

import api from '../services/api';
import Customer from '../components/Customer';

function Customers() {
    const history = useHistory();
    const [customers, setCustomers] = useState([]);
    const [metaData, setMetaData] = useState({});

    useEffect(() => {
        getCustomers();
    }, []);

    const getCustomers = async (page = 1) => {
        await api.get('/customers?page=' + page,
            {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`
                }
            }
        ).then(async (res) => {
            await setMetaData(res.data.meta);
            await setCustomers(res.data.data);
        }).catch(() => {
            localStorage.setItem('token', '');
            history.push('/');
        });
    };

    return (
        <div className="container">
            <div className="row justify-content-center">
                <div className="col-md-8 pt-5">
                    <h1 className="text-primary text-center">Customers</h1>
                    <div className="card border-0 text-center">
                        {!customers.length && (<div className="card-body">
                            <p className="card-text">No data found</p>
                        </div>)}

                        {!!customers.length && (
                            <div className="row">
                                {customers.map((customer) => (
                                    <Customer key={customer.id} customer={customer} listCallback={getCustomers}/>
                                ))}
                            </div>
                        )}

                        <div className="card-footer text-muted">
                            <div className="d-flex justify-content-center">
                                <ul className="pagination">
                                    {!!metaData.links && metaData.links.map((link) => (
                                        <>
                                            {!link.label.includes('&') &&
                                            <li className="page-item" key={link.label} onClick={() => {
                                                getCustomers(link.label)
                                            }}>
                                                <span className="page-link">{link.label}</span>
                                            </li>}
                                        </>
                                    ))}
                                </ul>
                            </div>
                            Showing from {metaData.from} to {metaData.to} of {metaData.total} results.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default Customers;

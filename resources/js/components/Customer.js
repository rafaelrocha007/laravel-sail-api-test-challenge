import React, {useState} from 'react';
import api from "../services/api";

function Customer({customer, listCallback}) {

    const [additionalData, setAdditionalData] = useState();

    const getAdditionalData = e => {
        e.preventDefault();

        api.get('/customers/' + customer.id, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}`
            }
        }).then((res) => {
            setAdditionalData(res.data)
            listCallback();
        }).catch(() => alert('Error retrieving customer data'))
    };

    return (
        <div className="col-12 pt-1">
            <div className="card border-primary m-2">
                <div className="card-header bg-white text-primary">
                    <div className="row">
                        <div className="col-11">
                            <h4 className="py-2 my-0 text-left">
                                {customer.first_name} {customer.last_name}
                            </h4>
                        </div>
                        <div className="col-1 text-center">
                            <a className="btn btn-link" onClick={getAdditionalData}>
                                <i className="fa fa-eye"/>
                            </a>
                        </div>
                    </div>
                </div>
                {
                    additionalData &&
                    <div className="card-footer bg-white d-flex justify-content-between align-items-between">
                        <div className="text-primary">
                            <i className={`fa fa-${additionalData.gender.toLowerCase()}`}/>
                            &nbsp;
                            {additionalData.gender}
                        </div>
                        <div className="text-primary">
                            <a href={'mailto:' + additionalData.email}>
                                <i className="fa fa-envelope"/>
                                &nbsp;
                                {additionalData.email}
                            </a>
                        </div>
                        <div className="text-primary">
                            <i className="fa fa-suitcase"/>
                            &nbsp;
                            {additionalData.title} at {additionalData.company}
                        </div>
                        <div className="text-primary">
                            <a href={`https://www.google.com/maps/@${additionalData.latitude},${additionalData.longitude},15z`}>
                                <i className="fa fa-map-marker"/>
                                &nbsp;
                                {additionalData.city}, {additionalData.state}
                            </a>
                        </div>
                    </div>
                }
            </div>
        </div>
    );
}

export default Customer;

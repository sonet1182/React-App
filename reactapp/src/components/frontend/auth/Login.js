import React, {useState} from "react";
import {Link} from 'react-router-dom';
import {useHistory} from 'react-router-dom';
import Navbar from "../../../layouts/frontend/Navbar";
import axios from "axios";
import swal from 'sweetalert';

function Login() {

    const history = useHistory();
    const [loginInput, setLogin] = useState({
        email: '',
        password: '',
        error_list: []
    });

    const handleInput = (e) => {
        e.persist();
        setLogin({...loginInput, [e.target.name]: e.target.value});
    }

    const loginSubmit = (e) => {
        e.preventDefault();
        const data = {
            email: loginInput.email,
            password: loginInput.password,
        }

        axios.get('/sanctum/csrf-cookie').then(response => {
            axios.post(`/api/login`, data).then(res => {
                if(res.data.status === 200)
                    {
                        localStorage.setItem('auth_token', res.data.token);
                        localStorage.setItem('auth_name', res.data.username);
                        swal("Success",res.data.message,"success");
                        history.push('/');
                    }
                    else if(res.data.status === 401)
                    {
                        swal("Warning",res.data.message,"warning");
                    }
                    else{
                        setLogin({ ...loginInput,error_list: res.data.validation_errors });
                    }
            });
        });
        
    }

    return (
        <div>
            <Navbar></Navbar>
            

                <div className="container py-5">
                    <div className="row justify-content-center">
                        <div className="col-md-6">
                        <div className="card">
                            <div className="card-header">
                                <h4>Login</h4>
                            </div>
                            <div className="card-body">
                            <form onSubmit={loginSubmit}>
                                    
                                    <div class="form-group mb-3">
                                        <label for="exampleInputEmail1">Email address</label>
                                        <input type="email" name="email" onChange={handleInput} value={loginInput.email} class="form-control"  placeholder="Enter email"/>
                                        <span>{loginInput.error_list.email}</span>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="exampleInputPassword1">Password</label>
                                        <input type="password" name="password" onChange={handleInput} value={loginInput.password} class="form-control"  placeholder="Password"/>
                                        <span>{loginInput.error_list.password}</span>
                                    </div>
                                    
                                    
                                    <button type="submit" class="btn btn-primary">Login</button>
                                </form>
                            </div>

                            </div>

                        </div>
                    </div>

                

                
            </div>
        
        
        </div>
    );

}

export default Login;
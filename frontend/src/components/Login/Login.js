import { useState, useEffect } from 'react'
import axios from 'axios'
import { useNavigate } from 'react-router-dom';
import { apiURL } from '../../utils/constance';

const Login = () => {

    const [username, setUsername] = useState("");
    const [password, setPassword] = useState("");
    const [error, setError] = useState("");
    const navigate  = useNavigate();

    useEffect(() => {
        const user = JSON.parse(localStorage.getItem('user'))
        if(user){
            navigate(`/${user.role}`)
        }
    }, [navigate])

    const handleLogin = async () => {
        setError("")
        try {

            if(!username || !password){
                setError("Please enter credentials.")
                return;
            }
            try {
                const response = await axios.post(apiURL+"login", {
                    email: username,
                    password: password,
                }, {
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                    }
                });
        
                if(response?.data?.token){
                    const { token, user } =  response.data
                    localStorage.setItem("token", token);
                    localStorage.setItem("user", JSON.stringify(user));
    
                    navigate(`/${user.role}`)
                }else{
                    setError(response?.data?.error[0])
                }
               
                console.log("Response Data:", response.data);
            } catch (error) {
                console.error("Login Failed:", error.response ? error.response.data : error.message);
            }
        } catch(error) {
            console.log("error", error)
            setError("something");
            return
        }
    }

    return (
        <div className="absolute text-center w-screen">
            <div className="text-center w-2/4 mb-5 mx-auto">
                <h2 className="text-4xl text-center mt-20 my-5">Login</h2>
                <div className="flex justify-center items-center gap-4 mb-5">
                    <label>Username:</label>
                    <input className="border-2 p-2 mr-2 w-2/4" name="email" id="email" type="email" placeholder="Email" onChange={(e) => setUsername(e.target.value)} />
                </div>
                <div className="flex justify-center items-center gap-4 mb-5">
                    <label>Password:</label>
                    <input className="border-2 p-2 mr-2 w-2/4" name="password" id="password" type="password" placeholder="Password" onChange={(e) => setPassword(e.target.value)} />
                </div>
                <div className="relative right-0">
                    <button className="px-6 py-2 border-2 w-1/4 text-black hover:bg-orange-300 ml-[274px]" onClick={handleLogin}>Login</button>
                </div>
            </div>
            { error && <div className="text-red-400 w-3/4 text-end ml-10">{error}</div> }
        </div>
      );
}

export default Login
import { useState, useEffect } from 'react'
import axios from 'axios'
import { useNavigate } from 'react-router-dom';

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
                const response = await axios.post("http://127.0.0.1:8000/api/login", {
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
        <div>
            <div className="text-center">
                <h2 className="text-4xl text-center mt-20 my-5">Login</h2>
                <input className="border-2 p-2 mr-2" type="email" placeholder="Email" onChange={(e) => setUsername(e.target.value)} />
                <input className="border-2 p-2 mr-2" type="password" placeholder="Password" onChange={(e) => setPassword(e.target.value)} />
                <button className="px-6 py-2 border-2  text-black hover:bg-orange-300" onClick={handleLogin}>Login</button>
            </div>
            { error && <div className="text-red-400">{error}</div> }
        </div>
      );
}

export default Login
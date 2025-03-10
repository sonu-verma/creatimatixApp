import React from 'react'
import { Navigate } from 'react-router-dom'

const ProtectedRoute = ({ children, role}) => {
    const  user = JSON.parse(localStorage.getItem('user'));

    if(!user){
        return <Navigate to='/login' replace />
    }

    if(user.role !== role){
        return <Navigate to={`/${user.role}`} replace />
    }

    return children
}

export default ProtectedRoute
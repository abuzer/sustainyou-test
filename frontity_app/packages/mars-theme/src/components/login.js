import axios from "axios";
import { styled } from "frontity";
import { useState } from 'react'
import { API_URL } from '../../../../utils/constants'

const Login = ({ state }) => {


    const [password, setPassword] = useState('');
    const [username, setUsername] = useState('');

    const handleSubmit = async (e) => {
        e.preventDefault();
        const config = {
            headers: {
                "Content-Type": "application/json",

            },
        };
        const body = JSON.stringify({ username, password });
        try {
            // login user
            const res = await axios.post(
                `${API_URL}/login`,
                body,
                config
            );

            localStorage.setItem('wp_user', JSON.stringify(res?.data?.data));
            if( res.data.statusCode == 200 ){
                alert('Login successfully. Click Travel link for now.');    
            }else{
                alert('Incorrect credentials.');    
            }


        } catch (error) {
            console.log(error);
            alert(error.response?.data?.message);


        }

    }

    const handleChange = (e) => {
        e.target.name === 'email' ? setUsername(e.target.value) : setPassword(e.target.value)
    }

    return (<Container>
        <div>
            <Title >
                Login
            </Title>
            <form onSubmit={handleSubmit} >


                <Input placeholder='email' required type='email' name='email' onChange={handleChange} />
                <Input placeholder='password' required type='password' name='password' onChange={handleChange} />
                <Button type='submit'>Submit</Button>
            </form>
        </div>
    </Container>
    )
}

export default Login;

const Button = styled.button`

  display:block;
  margin-bottom:10px;
  padding: 10px;
  cursor:pointer;
`;
const Input = styled.input`
  width: 30%;
  display:block;
  margin-bottom:10px;
  padding: 10px;
`;
const Container = styled.div`
  width: 800px;
  margin: 0;
  padding: 24px;
`;

const Title = styled.h1`
  margin: 0;
  margin-top: 24px;
  margin-bottom: 8px;
  color: rgba(12, 17, 43);
`;
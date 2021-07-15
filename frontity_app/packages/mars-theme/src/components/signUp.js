import axios from "axios";
import { styled, connect } from "frontity";
import { useState } from 'react'
import { API_URL } from '../../../../utils/constants'


const Signup = ({ state, actions, libraries }) => {

    const [data, setData] = useState({
        username: '',
        first_name: '',
        last_name: '',
        email: '',
        password: '',
        confirm_password: ''
    })

    const handleSubmit = async (e) => {
        e.preventDefault();
        if (data?.password !== data?.confirm_password) {
            return alert('password not matched')
        }
        const config = {
            headers: {
                "Content-Type": "application/json"
            },
        };
        const body = JSON.stringify({ ...data });
        try {
            // regist user
            await axios.post(
                `${API_URL}/register`,
                body,
                config
            );
            setData({
                username: '',
                first_name: '',
                last_name: '',
                email: '',
                password: '',
                confirm_password: ''
            });
            alert('signup successfully');
        } catch (error) {
            console.log(error?.response);
            alert(error.response?.data?.message);

        }

    }

    const handleChange = (e) => {

        setData({ ...data, [e.target.name]: e.target.value })
        // e.target.name === 'email' ? setEmail(e.target.value) : e.target.name === 'username' ? setUsername(e.target.value) : setPassword(e.target.value);

    }

    return (<Container>
        <div>
            <Title >
                Signup
            </Title>
            <form onSubmit={handleSubmit} >

                <Input placeholder='username' required type='text' name='username' onChange={handleChange} value={data?.username} />
                <Input placeholder='first name' required type='text' name='first_name' onChange={handleChange} value={data?.first_name} />
                <Input placeholder='last name' required type='text' name='last_name' onChange={handleChange} value={data?.last_name} />
                <Input placeholder='email' required type='email' name='email' onChange={handleChange} value={data?.email} />
                <Input placeholder='password' required type='password' name='password' onChange={handleChange} value={data?.password} />
                <Input placeholder='confirm password' required type='password' name='confirm_password' onChange={handleChange} value={data?.confirm_password} />
                <Button type='submit'>Submit</Button>
            </form>
        </div>
    </Container>
    )
}

export default connect(Signup);

const Button = styled.button`

  display:block;
  margin-bottom:10px;
  padding: 10px;
  cursor:pointer
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
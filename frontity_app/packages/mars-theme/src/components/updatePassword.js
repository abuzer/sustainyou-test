import axios from "axios";
import { styled } from "frontity";
import { useState } from 'react'
import { API_URL } from '../../../../utils/constants'


const UpdatePassword = ({ state }) => {

    const [password, setPassword] = useState('');

    const handleSubmit = async (e) => {
        e.preventDefault();
        const user = await JSON.parse(localStorage.getItem('wp_user'));

        const config = {
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${user?.token}`,

            },
        };
        const body = JSON.stringify({ new_password: password });
        try {
            // login user
            const res = await axios.post(
                `${API_URL}/reset-password`,
                body,
                config
            );

            // localStorage.setItem('wp_user', JSON.stringify(res?.data?.data)); 
            alert('Password updated successfully. Please relogin');
            // state.router.link = "/travel/"
        } catch (error) {
            console.log(error);
            alert(error.response?.data?.message);


        }

    }

    const handleChange = (e) => {
        setPassword(e.target.value)
    }

    return (<Container>
        <div>
            <Title >
                Update password
            </Title>
            <form onSubmit={handleSubmit} >
                <Input placeholder='password' required type='password' name='password' onChange={handleChange} />
                <Button type='submit'>Update Password</Button>
            </form>
        </div>
    </Container>
    )
}

export default UpdatePassword;

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
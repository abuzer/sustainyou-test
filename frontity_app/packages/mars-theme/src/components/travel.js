import axios from "axios";
import { styled, connect } from "frontity";
import { useEffect, useState } from 'react'


const Travel = ({ state }) => {
  const [userData, setUserData] = useState({})

  const authUser = async () => {
    const user = await JSON.parse(localStorage.getItem('wp_user'));

    if (!user?.token) {
      state.router.link = "/login/"
    } else {
      setUserData(user);
    }
  }

  useEffect(() => {
    authUser()
  }, [])

  return (<Container>
    <div>
      <p><b>Name : {userData?.displayName}</b></p>
      <p><b>Email : {userData?.email}</b></p>
      <Button onClick={() => { localStorage.removeItem('wp_user'); authUser() }} >Logout</Button>

    </div>
  </Container>
  )
}

export default connect(Travel);

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
const settings = {
  "name": "sub-site",
  "state": {
    "frontity": {
      "url": "https://test.frontity.org",
      "title": "Test Frontity Blog",
      "description": "WordPress installation for Frontity development"
    }
  },
  "packages": [
    {
      "name": "@frontity/mars-theme",
      "state": {
        "theme": {
          "menu": [
            [
              "Signup",
              "/"
            ],
            [
              "Login",
              "/login"
            ],
            [
              "Update Password",
              "/update-password/"
            ],
            [
              "Profile",
              "/travel/"
            ],
            // [
            //   "Japan",
            //   "/tag/japan/"
            // ],
            // [
            //   "About Us",
            //   "/about-us/"
            // ]
          ],
          "featured": {
            "showOnList": false,
            "showOnPost": false
          }
        }
      }
    },
    {
      "name": "@frontity/wp-source",
      "state": {
        "source": {
          "url": "http://localhost/testsite/",
          data: {
            "/login/": {
              isReady: true,
              isFetching: false,
              isLogin: true,
            },
            "/travel/": {
              isReady: true,
              isFetching: false,
              isTravel: true,
            },
            "/update-password/": {
              isReady: true,
              isFetching: false,
              isUpdatePassword: true,
            },
            "/": {
              isReady: true,
              isFetching: false,
              isSignUp: true,
            },

          },

        }
      }
    },
    "@frontity/tiny-router",
    "@frontity/html2react"
  ]
};

export default settings;

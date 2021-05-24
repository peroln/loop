<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
<style>
  pre {
    font-family: "Courier 10 Pitch", Courier, monospace;
    font-size: 95%;
    line-height: 140%;
    white-space: pre;
    white-space: pre-wrap;
    white-space: -moz-pre-wrap;
    white-space: -o-pre-wrap;
    display: block;
    padding: 0.5em 1em;
    border-left: 1px solid #bebab0;
    background-color: #e7e7e740;
  }

  .log {
    font-family: 'Open Sans', sans-serif;
    font-size: 95%;
    line-height: 140%;
    white-space: pre;
    white-space: pre-wrap;
    white-space: -moz-pre-wrap;
    white-space: -o-pre-wrap;
    display: block;
    padding: 0.5em 1em;
    border-top: 1px solid #68e400;
    border-bottom: 1px solid #68e400;
    background-color: #3c3c3c;
    margin-top: 10px;
    margin-bottom: 10px;
    overflow-y: auto;
    height: 400px;
    color: white;
  }

  .container {
    background: white;
    margin-top: 20px;
    margin-left: 60px;
    margin-right: 60px;
    padding: 30px;
    border-radius: 5px;
    color: black;
  }

  .line {
    width: 100%;
    border-bottom: 1px black solid;
    margin-top: 10px;
    margin-bottom: 10px;
  }

  body {
    font-family: 'Roboto', sans-serif;
    background: #f8f9fa;
  }
</style>
<div class="container">
    <h1>DEBUG SOCKETS PANEL</h1>
    <div class="line"></div>

    <h2> README.md </h2>

    How to use sockets on client side
    <pre>
    FOR MORE <a href="https://laravel.su/docs/5.4/broadcasting">https://laravel.su/docs/5.4/broadcasting</a>
        <a href="https://www.javascripting.com/view/pusher-js">https://www.javascripting.com/view/pusher-js</a> -> Connection States
        Binding on events <a
                href="https://stackoverflow.com/questions/62153997/binding-callbacks-on-laravel-echo-with-laravel-websockets">https://stackoverflow.com/questions/62153997/binding-callbacks-on-laravel-echo-with-laravel-websockets</a>
    var YourTokenLogin = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODk1MFwvYXBpXC91c2VyXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYyMTY5NjU3MywiZXhwIjoxNjIxNzAwMTczLCJuYmYiOjE2MjE2OTY1NzMsImp0aSI6IlkxMHp4eXdnSEl0QWtXVnkiLCJzdWIiOjEsInBydiI6ImNjYWY4ZDgwZDE1OGQwMTY1ODAyN2U1MTEzN2MwZmY0NGQzNjcwMzcifQ.Bw2u7Tpk5tsLbx0QtEwNcu--E0NjnLwDKRS7xlyGyBc'; //Bearer token
    var echo = new Echo({
           broadcaster: 'pusher',
            wsHost: window.location.hostname,
            wsPort: 8811,
            key: 'c7eff08f02639017cf57',
            forceTLS: false,
            disableStats: true,
            auth: {
                headers: {
                    Authorization: 'Bearer ' + token
                }
            }
    });

        echo.connector.pusher.connection.bind('failed', (e) => {
            logger('failed: ', e)
        });

        echo.connector.pusher.connection.bind('disconnected', (e) => {
            logger('disconnected: ', e)
        });

        echo.connector.pusher.connection.bind('unavailable', (e) => {
            logger('unavailable: ', e)
        });

        echo.connector.pusher.connection.bind('connecting_in', (e) => {
            logger('connecting_in: ', e)
        });

        echo.connector.pusher.connection.bind('message', (e) => {
            logger('message: ', e)
        });

        echo.connector.pusher.connection.bind('connecting', (attemptNumber) => {
            //your code
            logger(`%cSocket reconnecting attempt ${attemptNumber}`, 'color:orange; font-weight:700;')
        });

        echo.connector.pusher.connection.bind('connected', (e) => {
            logger("Client connected ", e);
        });


        echo.private(`debug`)
                .listen('TestEvent', (e) => {
                    logger('received: ', e)
                });

        echo.private(`user.{id}`)
                .notification((e) => {
                    logger('received: ', e)
                });
</pre>

    <div class="line"></div>
    <h4>DEBUG PANEL</h4>
    <div class="line"></div>

    <h6>SERVER ADDRESSES</h6>

    API_HOST = <input onchange="setAuthHost(this.value)" id="API_HOST" value="http://127.0.0.1:8950"> <br /><br />
    SOCKET_HOST = <input onchange="setHost(this.value)" id="SOCKET_HOST" value="http://127.0.0.1:8084"> <br />

    <hr />

    <script>
        let client = false;
        let host = document.getElementById('SOCKET_HOST').value;
        let authHost = document.getElementById('API_HOST').value;

        function setHost(value) {
            host = value;
        }

        function setAuthHost(value) {
            authHost = value;
        }

        let logs_count = 0;

        function logger(...args) {
            console.log(args);
            document.getElementById('logs').append(`${logs_count} | ${JSON.stringify(args)} \n`);
            logs_count++;
        }

        function getInfo() {
            let appId = document.getElementById('appId').value;
            let appKey = document.getElementById('appKey').value;
            try {
                fetch(`${host}/apps/${appId}/status?auth_key=${appKey}`).then(function (res) {
                    res.json().then(json => document.getElementById('status').append(`${JSON.stringify(json)} \n`))
                })
                fetch(`${host}/apps/${appId}/channels?auth_key=${appKey}`).then(function (res) {
                    res.json().then(json => document.getElementById('channels').append(`${JSON.stringify(json)} \n`))
                })
            } catch (e) {
                logger(e.message)
            }
        }

        function connectToChannel() {
            let channel_name = document.getElementById('channel_name').value;
            let event = document.getElementById('event').value;
            console.log(event);
            console.log(client);
            client.private(channel_name)
                .listen(event, (e) => {
                    logger('Event received: ', e)
                }).notification((res) => {
                logger('Event notification received: ', res)
            });
        }

        function connectToNotificationChannel() {
            let channel_name = document.getElementById('notifications_channel_name').value;
            client.private(channel_name)
                .notification((e) => {
                    logger('Notification received: ', e);
                });
        }

        function connectGroupChannels() {
            let channel_name = document.getElementById('groups_channel_name').value;
            client.join(channel_name).here(users => {
                logger('Group users : ', users)
            }).joining((res) => {
                logger('Group response :', res)
            });
        }
    </script>

    <h6>GET BEARRER ACCSESS TOKEN FOR USING TOKEN</h6>

    Hi dear friend, firstly auth in system for using sockets
    <br /><br />
    USER AUTH ROUTE: <input id="auth_route" name="auth_route" value="/api/user/auth/login" /> <br /> <br />
    <form id="auth">
        <input name="address" value="TKKn6cywjhDzjCNqTY3qKwjnvfoXGsF6Xe" placeholder="username" /> <input name="password" value="user1Qwerty_"
                placeholder="Password" /> <input name="deviceId" value="deviceqwerty123" placeholder="deviceId" />
        <input type="submit">
    </form>
    <div class="line"></div>
    ADMIN AUTH ROUTE: <input id="admin_auth_route" name="admin_auth_route" value="/api/admin/login" /> <br /> <br />
    <form id="authAdmin">
        <input name="emailAdmin" value="TKKn6cywjhDzjCNqTY3qKwjnvfoXGsF6Xe" /> <input name="passwordAdmin" /> <input type="submit">
    </form>

    </form>
    <div id="login_status"></div>

    <hr />

    <h5>SOCKET INFO</h5>
    APP_ID <input id="appId" name="appId" value="test" placeholder="AppId" /> <br /><br /> APP_KEY <input id="appKey"
            name="authKey" value="b8a3aa6678dc21c482bbe4f1c9ea11b8" />
    <button type="button" onclick="getInfo()">Load</button>
    <br /><br /> <span>Status</span>
    <pre id="status"></pre>
    <span>Channels</span>
    <pre id="channels"></pre>

    <hr />

    <h5> BASE INIT CHANNELS </h5>
    DEBUG CHANNEL: <input id="debug_channel_name" name="debug_channel_name" value="debug" /> <br /><br /> DEBUG EVENT:
    <input id="debug_event_name" name="debug_event_name" value="TestEvent" />

    <h5>CONNECT TO CHANNEL</h5>

    GROUPS CHANNEL <input id="groups_channel_name" name="groups_channel_name" value="shipper" />
    <button type="button" onclick="connectGroupChannels()">Listen groups channel</button>
    <hr />
    <br /> <br />

    NOTIFICATIONS CHANNEL <input id="notifications_channel_name" name="notifications_channel_name" value="shippers.1" />
    <button type="button" onclick="connectToNotificationChannel()">Listen notifications</button>
    <hr />
    <h6>CONNECT TO CHANNEL FOR LISTEN EVENTS</h6>
    CHANNEL <input id="channel_name" name="channel_name" value="debug" /> <br /> <br />

    EVENT <input id="event" name="event"
            value="TestEvent" />
    <button type="button" onclick="connectToChannel()">Connect</button>

    <div class="line"></div>
    <h4>CHANNEL LOGS</h4>
    <div class="log" id="logs"></div>
</div>
<script type="module" src="/api/debug/libs/echo"></script>
<script src="/api/debug/libs/io"></script>
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>

<script type="module">
    import Echo from '/api/debug/libs/echo';

    let user = false;

    window.addEventListener('error', ev => logger(ev.message));
    document.getElementById('auth').addEventListener('submit', login);
    document.getElementById('authAdmin').addEventListener('submit', loginAdmin);
    Pusher.logToConsole = true;
    function initEcho(token = '') {
        console.log(window.location.hostname);
        console.log(token);
        client = new Echo({
            broadcaster: 'pusher',
            wsHost: window.location.hostname,
            wsPort: 8084,
            key: 'c7eff08f02639017cf57',
            forceTLS: false,
            disableStats: true,
            auth: {
                headers: {
                    Authorization: 'Bearer ' + token
                }
            }
        });
        window.client = client;

        console.log(client.connector);
        client.connector.pusher.connection.bind('failed', (e) => {
            logger('failed: ', e)
        });

        client.connector.pusher.connection.bind('disconnected', (e) => {
            logger('disconnected: ', e)
        });

        client.connector.pusher.connection.bind('unavailable', (e) => {
            logger('unavailable: ', e)
        });

        client.connector.pusher.connection.bind('connecting_in', (e) => {
            logger('connecting_in: ', e)
        });

        client.connector.pusher.connection.bind('message', (e) => {
            logger('message: ', e)
        });

        client.connector.pusher.connection.bind('connecting', (attemptNumber) => {
            //your code
            logger(`%cSocket reconnecting attempt ${attemptNumber}`, 'color:orange; font-weight:700;')
        });

        client.connector.pusher.connection.bind('connected', (e) => {
            logger("Client connected ", e);
            document.getElementById('login_status').append("Client connected \n");
        });


        client.private(document.getElementById('debug_channel_name').value)
            .listen(document.getElementById('debug_event_name').value, (e) => {
                logger('Event received: ', e)
            });
    }

    function login(e) {
        console.log('Login function');
        try {
            e.preventDefault();
            let authRoute = document.getElementById('auth_route').value;
            let formData = new FormData(document.getElementById('auth'))
            let data = {
                address: formData.get('address'),
               /* userName: formData.get('email'),
                password: formData.get('password'),*/
            };
            if (formData.has('deviceId')) {
                data.deviceId = formData.get('deviceId');
            }
            fetch(`${authHost}${authRoute}`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            }).then(res => {
                if (!res.ok) {
                    res.text().then(logger)
                } else {
                    res.json().then(data => {
                        user = data;
                        initEcho(data.token)
                    })
                }
            });
        } catch (e) {
            logger(e.message)
        }
    }

    function loginAdmin(e) {
        try {
            e.preventDefault();
            let formData = new FormData(document.getElementById('authAdmin'));
            let authRoute = document.getElementById('admin_auth_route').value;
            fetch(`${authHost}${authRoute}`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({email: formData.get('emailAdmin'), password: formData.get('passwordAdmin')})
            }).then(res => {
                if (!res.ok) {
                    res.text().then(logger)
                } else {
                    res.json().then(data => {
                        user = data;
                        initEcho(data.token)
                    })
                }
            });
        } catch (e) {
            logger(e.message)
        }
    }
</script>

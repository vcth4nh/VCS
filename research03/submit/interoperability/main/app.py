from flask import Flask, request, render_template
import requests
import ujson
import os

app = Flask(__name__)

flag = "flag{hehehe}"


@app.route('/')
def index():
    return render_template("index.html")


@app.route('/api/login', methods=["POST"])
def login():
    username = ujson.loads(request.data)["username"]
    if username == "admin" and request.remote_addr != "127.0.0.1":
        return "admin can only login locally"

    if requests.post(f"http://{os.getenv('VERIFY')}:1338/api/login", data=request.data).text == "True":
        return flag

    return ("no flag 4 you")


if __name__ == "__main__":
    app.run(host='0.0.0.0', port=1337, debug=False)

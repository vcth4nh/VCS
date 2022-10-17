from flask import Flask, request
import ujson

app = Flask(__name__)


@app.route('/api/login', methods=["POST"])
def login():
    return str(ujson.loads(request.data)["username"] == "admin")


if __name__ == "__main__":
    app.run(host='0.0.0.0', port=1338, debug=False)

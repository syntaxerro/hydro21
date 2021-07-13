export class Api extends EventTarget {
  connect(address) {
    this.ws = new WebSocket(address);
    this.ws.onmessage = (ev) => this.message(ev);
    this.ws.onerror = (ev) => this.error(ev);
    this.ws.onclose = (ev) => this.close(ev);
    this.ws.onopen = (ev) => this.open(ev);
  }

  send(controller, data) {
    this.ws.send(
      JSON.stringify({
        controller,
        ...data,
      })
    );
  }

  open() {
    this.send('register_client');
  }

  message(ev) {
    const data = JSON.parse(ev.data);
    const controller = data.controller;
    delete data.controller;

    const newEvent = new Event(controller);
    newEvent.data = data;

    this.dispatchEvent(newEvent);
  }

  error(err) {
    if (err) {
      alert('connect to server failed');
    }
  }

  close(ev) {
    alert('connection closed');
  }
}

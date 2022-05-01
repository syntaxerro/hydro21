import { env } from './env.js';

export class Api extends EventTarget {
  connect() {
    this.ws = new WebSocket(env.socketAddress);

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

  reconnect(seconds) {
    if (seconds) {
      setTimeout(() => {
        this.reconnect(seconds - 1);
      }, 1000);
    } else {
      this.connect();
    }
  }

  error(err) {}

  close(ev) {
    this.reconnect(10);
  }
}

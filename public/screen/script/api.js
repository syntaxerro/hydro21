export class Api {
  constructor(address) {
    this.ws = new WebSocket(address);
    this.ws.onmessage = this.message;
    this.ws.onerror = this.error;
    this.ws.onclose = this.close;
    this.ws.onopen = this.open;
  }

  open(ev) {
    console.log(ev);
  }

  message(ev) {
    console.log(ev);
  }

  error(ev) {
    console.log(ev);
  }

  close(ev) {
    console.log(ev);
  }
}

import { Api } from './api.js';

export class Controller extends Api {
  constructor() {
    super();
    this.$container = document.querySelector('.api-intersection');
    this.$message = this.$container.querySelector('.message');
    this.$caption = this.$container.querySelector('.message-caption');
  }

  connect() {
    super.connect();
    this.changeMessage({ message: 'Łączenie się z kontrolerem' });
  }

  open() {
    super.open();
    this.changeMessage({ message: '', caption: '' });
  }

  changeMessage({ message, caption }) {
    if (typeof caption === 'string') {
      this.$caption.textContent = caption;
    }

    if (typeof message === 'string') {
      this.$message.textContent = message;
    }

    if (this.$caption.textContent || this.$message.textContent) {
      this.$container.style.display = '';
    } else {
      this.$container.style.display = 'none';
    }
  }

  reconnect(seconds) {
    super.reconnect(seconds);

    this.changeMessage({
      caption: seconds ? `Ponowne połączenie za ${seconds}s` : '',
    });
  }

  close(ev) {
    super.close(ev);

    switch (ev.code) {
      case 1006:
        this.changeMessage({ message: 'Błąd połączenia z serwerem' });
        break;
      default:
        this.changeMessage({ message: 'Błąd połączenia z serwerem' });
    }
  }
}

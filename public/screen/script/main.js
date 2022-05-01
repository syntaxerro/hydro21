import { Controller } from './controller.js';
import { Interface } from './interface.js';
import { setup } from './setup.js';

const api = new Controller();
new Interface(api);
setup(api);

api.addEventListener('system_status', (ev) => {});

api.connect();
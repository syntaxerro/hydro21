import { env } from './env.js';
import { Api } from './api'

import '../style/main.css';

const api = new Api(env.socketAddress);
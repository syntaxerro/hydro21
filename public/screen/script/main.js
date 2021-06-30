import { env } from './env.js';
import { Api } from './api';
import {
  valves,
  pipes,
  pump,
  valvesAfterPipes,
  valvesBeforePipes,
} from './setup.js';
// treeshaking

import '../style/main.css';

const api = new Api(env.socketAddress);

valves.main.attach(pipes[0]);

pump.attach(pipes[1]);
pipes[1].attach(pipes[2], ...valvesBeforePipes);

valves.fourth.attach(valvesAfterPipes[0]);
valves.third.attach(valvesAfterPipes[1]);
valves.second.attach(valvesAfterPipes[2]);
valves.first.attach(valvesAfterPipes[3]);

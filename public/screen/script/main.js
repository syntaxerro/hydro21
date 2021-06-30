import { env } from './env.js';
import { Api } from './api.js';
import {
  valves,
  pipes,
  pump,
  valvesAfterPipes,
  valvesBeforePipes,
} from './setup.js';
// treeshaking

// import '../style/main.css';

const api = new Api();

valves.main.svg.onclick = null;

pump.attach(pipes[0], pipes[1]);
pipes[1].attach(pipes[2], ...valvesBeforePipes);

valves.ch4.attach(valvesAfterPipes[0]);
valves.ch3.attach(valvesAfterPipes[1]);
valves.ch2.attach(valvesAfterPipes[2]);
valves.ch1.attach(valvesAfterPipes[3]);

for (let valve in valves) {
  valves[valve].addEventListener('setValue', (res) => {
    api.send('set_valve', {
      state: res.data,
      valve,
    });
  });
}

api.addEventListener('system_status', (ev) => {
  console.log('system status', ev.data);
});

api.addEventListener('current_valves_states', (ev) => {
  for (let valve in ev.data) {
    valves[valve].setValue(ev.data[valve]);
  }
});

api.addEventListener('current_pump_state', ({ data: { speed } }) => {
  pump.setValue(speed > 0);
  $slider.disabled = false;
});

api.connect(env.socketAddress);

const $slider = document.querySelector('input');

$slider.onchange = () => {
  $slider.disabled = true;
  api.send('set_pump', {
    speed: $slider.value,
  });
};

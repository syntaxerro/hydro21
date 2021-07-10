import { env } from './env.js';
import { Api } from './api.js';
import { setup } from './setup.js';

const { valves, pipes, pump, valvesAfterPipes, valvesBeforePipes } = setup();
const api = new Api();

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
    const enabled = ev.data[valve];
    valves[valve].updateValue({ enabled });
  }

  updatePipes();
});

api.addEventListener('current_pump_state', ({ data: { speed } }) => {
  pump.updateValue({ pump: speed });
  $slider.disabled = false;
  $slider.value = speed;

  updatePipes();
});

function updatePipes() {
  const [ch4, ch3, ch2, ch1, main] = [
    valves.ch4.enabled,
    valves.ch3.enabled,
    valves.ch2.enabled,
    valves.ch1.enabled,
    valves.main.enabled,
  ];

  const p = pump.pump;

  [...pipes, ...valvesAfterPipes, ...valvesBeforePipes].forEach((pipe) => {
    pipe.svg.style = `animation-duration: ${550 - p * 4}ms`;
    console.log(`animation-duration: ${550 - p * 4}ms`);
  });

  valvesAfterPipes[0].updateValue({ enabled: ch4 && p, pump: p });
  valvesAfterPipes[1].updateValue({ enabled: ch3 && p, pump: p });
  valvesAfterPipes[2].updateValue({ enabled: ch2 && p, pump: p });
  valvesAfterPipes[3].updateValue({ enabled: ch1 && p, pump: p });

  valvesBeforePipes[0].updateValue({ pump: ch4, enabled: p });
  valvesBeforePipes[1].updateValue({ pump: ch3, enabled: p });
  valvesBeforePipes[2].updateValue({ pump: ch2, enabled: p });
  valvesBeforePipes[3].updateValue({ pump: ch1, enabled: p });

  pipes[1].updateValue({ pump: ch4 || ch3 || ch2 || ch1, enabled: p });
  pipes[2].updateValue({ pump: ch3 || ch2 || ch1, enabled: p });
  pipes[3].updateValue({ pump: ch2 || ch1, enabled: p });
  pipes[4].updateValue({ pump: ch1, enabled: p });

  pipes[0].updateValue({ enabled: main, pump: p });
}

api.connect(env.socketAddress);

const $slider = document.querySelector('input');

$slider.onchange = () => {
  $slider.disabled = true;
  api.send('set_pump', {
    speed: $slider.value,
  });
};

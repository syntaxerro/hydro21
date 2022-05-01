import { Valve } from './svg/valve.js';
import { Pipe } from './svg/pipe.js';
import { Pump } from './svg/pump.js';

export function setup(api) {
  const valvesY = 92;

  const pump = new Pump({ x: 725, y: 340 });

  const valves = {
    main: new Valve({ x: 48, y: 357, horizontal: true }),
    ch1: new Valve({ x: 48, y: valvesY }),
    ch2: new Valve({ x: 280, y: valvesY }),
    ch3: new Valve({ x: 490, y: valvesY }),
    ch4: new Valve({ x: 700, y: valvesY }),
  };

  const underValvesY = valvesY + 168;
  const overValvesY = 0;
  const underValvesStart = { x: pump.center.x, y: underValvesY };
  const underValvesEnd = { x: valves.ch1.center.x, y: underValvesY };

  const pipes = [
    new Pipe([{ x: 0, y: valves.main.center.y }, pump.center]),
    new Pipe([pump.center, underValvesStart]),
    new Pipe([
      { x: valves.ch4.center.x, y: underValvesY },
      { x: valves.ch3.center.x, y: underValvesY },
    ]),
    new Pipe([
      { x: valves.ch3.center.x, y: underValvesY },
      { x: valves.ch2.center.x, y: underValvesY },
    ]),
    new Pipe([{ x: valves.ch2.center.x, y: underValvesY }, underValvesEnd]),
  ];

  const valvesBeforePipes = [
    new Pipe([{ x: valves.ch4.center.x, y: underValvesY }, valves.ch4.center]),
    new Pipe([{ x: valves.ch3.center.x, y: underValvesY }, valves.ch3.center]),
    new Pipe([{ x: valves.ch2.center.x, y: underValvesY }, valves.ch2.center]),
    new Pipe([{ x: valves.ch1.center.x, y: underValvesY }, valves.ch1.center]),
  ];

  const valvesAfterPipes = [
    new Pipe([valves.ch4.center, { x: valves.ch4.center.x, y: overValvesY }]),
    new Pipe([valves.ch3.center, { x: valves.ch3.center.x, y: overValvesY }]),
    new Pipe([valves.ch2.center, { x: valves.ch2.center.x, y: overValvesY }]),
    new Pipe([valves.ch1.center, { x: valves.ch1.center.x, y: overValvesY }]),
  ];

  [...pipes, ...valvesBeforePipes, ...valvesAfterPipes].forEach((element) => {
    element.appendBackground();
  });

  [...pipes, ...valvesBeforePipes, ...valvesAfterPipes].forEach((element) => {
    element.append();
  });

  pump.append();

  for (let valve in valves) {
    valves[valve].append();
  }

  for (let valve in valves) {
    valves[valve].addEventListener('setValue', (res) => {
      api.send('set_valve', {
        state: res.data,
        valve,
      });
    });
  }

  api.addEventListener('current_valves_states', (ev) => {
    for (let valve in ev.data) {
      const enabled = ev.data[valve];
      valves[valve].updateValue({ enabled });
    }

    updatePipes();
  });

  api.addEventListener('current_pump_state', ({ data: { speed } }) => {
    pump.updateValue({ pump: speed });
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
      pipe.svg.style = `animation-duration: ${500 - p * 35}ms`;
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
}

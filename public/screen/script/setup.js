import { Valve } from './svg/valve.js';
import { Pipe } from './svg/pipe.js';
import { Pump } from './svg/pump.js';

export function setup() {
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

  return {
    valves,
    pipes,
    pump,
    valvesAfterPipes,
    valvesBeforePipes,
  };
}

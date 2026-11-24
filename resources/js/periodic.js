import * as THREE from 'three';
import * as TWEEN from '@tweenjs/tween.js';
import { TrackballControls } from 'three/examples/jsm/controls/TrackballControls.js';
import { CSS3DRenderer, CSS3DObject } from 'three/examples/jsm/renderers/CSS3DRenderer.js';

const COLORS = { red: '#EF3022', orange: '#F7931E', green: '#3A9F48' };

// Spec: Red < $100K, Orange > $100K, Green > $200K
const colorFor = (nw) => (nw > 200000 ? COLORS.green : nw >= 100000 ? COLORS.orange : COLORS.red);

const fmtNW = (n) => '$' + Math.round(n).toLocaleString('en-US');

let camera, scene, renderer, controls;
const objects = [];
const targets = { table: [], sphere: [], helix: [], grid: [] };

init();

async function init() {
  const people = await fetch('/api/people', { headers: { Accept: 'application/json' } }).then((r) => r.json());

  camera = new THREE.PerspectiveCamera(40, innerWidth / innerHeight, 1, 10000);
  camera.position.z = 3000;
  scene = new THREE.Scene();

  people.forEach((p) => {
    const el = document.createElement('div');
    el.className = 'element';
    el.style.setProperty('--c', colorFor(p.netWorth));
    el.title = `${p.name} — ${fmtNW(p.netWorth)}`;
    el.innerHTML = `
      <div class="top"><span>${p.country}</span><span>${p.age}</span></div>
      <img src="${p.photo}" alt="" loading="lazy" referrerpolicy="no-referrer">
      <div class="name"></div>
      <div class="interest"></div>`;
    el.querySelector('.name').textContent = p.name;       // textContent: names come from a sheet
    el.querySelector('.interest').textContent = p.interest;

    const obj = new CSS3DObject(el);
    obj.position.set(Math.random() * 4000 - 2000, Math.random() * 4000 - 2000, Math.random() * 4000 - 2000);
    scene.add(obj);
    objects.push(obj);
  });

  buildTable(); buildSphere(); buildHelix(); buildGrid();

  renderer = new CSS3DRenderer();
  renderer.setSize(innerWidth, innerHeight);
  document.getElementById('container').appendChild(renderer.domElement);

  controls = new TrackballControls(camera, renderer.domElement);
  controls.minDistance = 500;
  controls.maxDistance = 8000;
  controls.addEventListener('change', render);

  for (const name of Object.keys(targets)) {
    document.getElementById(name).addEventListener('click', () => transform(targets[name], 2000));
  }

  addEventListener('resize', () => {
    camera.aspect = innerWidth / innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(innerWidth, innerHeight);
    render();
  });

  transform(targets.table, 2000);
  animate();
}

const target = (position, lookAt) => {
  const o = new THREE.Object3D();
  o.position.copy(position);
  if (lookAt) o.lookAt(lookAt);
  return o;
};

// TABLE — 20 columns x 10 rows
function buildTable() {
  const COLS = 20, DX = 140, DY = 180;
  objects.forEach((_, i) => {
    const col = i % COLS, row = Math.floor(i / COLS);
    targets.table.push(target(new THREE.Vector3((col - (COLS - 1) / 2) * DX, -(row - 4.5) * DY, 0)));
  });
}

// SPHERE
function buildSphere() {
  const l = objects.length, v = new THREE.Vector3();
  objects.forEach((_, i) => {
    const phi = Math.acos(-1 + (2 * i) / l);
    const theta = Math.sqrt(l * Math.PI) * phi;
    const pos = new THREE.Vector3().setFromSphericalCoords(900, phi, theta);
    v.copy(pos).multiplyScalar(2);
    targets.sphere.push(target(pos, v.clone()));
  });
}

// DOUBLE HELIX — two strands, 180° apart; even index -> strand A, odd -> strand B
function buildHelix() {
  const R = 900, STEP = 0.5, RISE = 16;
  const perStrand = Math.ceil(objects.length / 2);
  objects.forEach((_, i) => {
    const strand = i % 2, k = Math.floor(i / 2);
    const theta = k * STEP + strand * Math.PI;
    const pos = new THREE.Vector3(R * Math.sin(theta), -(k - perStrand / 2) * RISE, R * Math.cos(theta));
    targets.helix.push(target(pos, new THREE.Vector3(pos.x * 2, pos.y, pos.z * 2)));
  });
}

// GRID — 10 (x) x 4 (y) x 5 (z) = 200
function buildGrid() {
  const NX = 10, NY = 4, NZ = 5, DX = 200, DY = 220, DZ = 400;
  objects.forEach((_, i) => {
    const x = i % NX, y = Math.floor(i / NX) % NY, z = Math.floor(i / (NX * NY));
    targets.grid.push(target(new THREE.Vector3((x - (NX - 1) / 2) * DX, -(y - (NY - 1) / 2) * DY, (z - (NZ - 1) / 2) * DZ)));
  });
}

function transform(list, duration) {
  TWEEN.removeAll();
  objects.forEach((obj, i) => {
    const t = list[i];
    new TWEEN.Tween(obj.position)
      .to({ x: t.position.x, y: t.position.y, z: t.position.z }, Math.random() * duration + duration)
      .easing(TWEEN.Easing.Exponential.InOut).start();
    new TWEEN.Tween(obj.rotation)
      .to({ x: t.rotation.x, y: t.rotation.y, z: t.rotation.z }, Math.random() * duration + duration)
      .easing(TWEEN.Easing.Exponential.InOut).start();
  });
  new TWEEN.Tween({}).to({}, duration * 2).onUpdate(render).start();
}

function animate() {
  requestAnimationFrame(animate);
  TWEEN.update();
  controls.update();
}

function render() {
  renderer.render(scene, camera);
}

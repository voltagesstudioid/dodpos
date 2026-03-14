/**
 * Unit Tests: Sidebar State Persistence
 * File: resources/js/sidebar-state.test.js
 *
 * Run with: npx jest resources/js/sidebar-state.test.js
 * Or: npx vitest run resources/js/sidebar-state.test.js
 *
 * Requirements: jsdom environment (default in jest/vitest).
 */

const SIDEBAR_STATE_KEY = 'dodpos_sidebar_state';

// ─── Helpers ──────────────────────────────────────────────────────────────────

function createGroup(id, open = false) {
    const el = document.createElement('div');
    el.id = id;
    el.className = 'nav-group' + (open ? ' open' : '');
    document.body.appendChild(el);
    return el;
}

function loadState() {
    try {
        return JSON.parse(localStorage.getItem(SIDEBAR_STATE_KEY)) || {};
    } catch {
        return {};
    }
}

function saveState(state) {
    localStorage.setItem(SIDEBAR_STATE_KEY, JSON.stringify(state));
}

function toggleGroup(id, state) {
    const grp = document.getElementById(id);
    if (!grp) return state;
    const isOpen = grp.classList.toggle('open');
    state[id] = isOpen;
    saveState(state);
    return state;
}

function restoreState(state) {
    document.querySelectorAll('.nav-group').forEach((g) => {
        const id = g.id;
        const isBackendOpen = g.classList.contains('open');
        if (isBackendOpen) {
            state[id] = true;
        } else if (state[id] === true) {
            g.classList.add('open');
        } else if (state[id] === false) {
            g.classList.remove('open');
        }
    });
    saveState(state);
    return state;
}

// ─── Tests ────────────────────────────────────────────────────────────────────

beforeEach(() => {
    localStorage.clear();
    document.body.innerHTML = '';
});

describe('Sidebar State Persistence', () => {

    test('1. toggleGroup opens a closed group and persists to localStorage', () => {
        createGroup('grp-test', false);
        let state = loadState();
        state = toggleGroup('grp-test', state);

        const persisted = loadState();
        expect(persisted['grp-test']).toBe(true);
        expect(document.getElementById('grp-test').classList.contains('open')).toBe(true);
    });

    test('2. toggleGroup closes an open group and persists to localStorage', () => {
        createGroup('grp-test', true);
        let state = loadState();
        state['grp-test'] = true;
        state = toggleGroup('grp-test', state);

        const persisted = loadState();
        expect(persisted['grp-test']).toBe(false);
        expect(document.getElementById('grp-test').classList.contains('open')).toBe(false);
    });

    test('3. restoreState opens groups that were open in localStorage (not loaded from server)', () => {
        createGroup('grp-laporan', false);
        saveState({ 'grp-laporan': true });
        let state = loadState();
        restoreState(state);

        expect(document.getElementById('grp-laporan').classList.contains('open')).toBe(true);
    });

    test('4. restoreState closes groups forced closed in localStorage', () => {
        createGroup('grp-laporan', true);
        saveState({ 'grp-laporan': false });
        let state = loadState();
        restoreState(state);

        expect(document.getElementById('grp-laporan').classList.contains('open')).toBe(false);
    });

    test('5. deep link: server marks group open => it stays open and state is saved', () => {
        createGroup('grp-master', true); // server-rendered as open
        let state = loadState(); // fresh, no saved state
        restoreState(state);

        const persisted = loadState();
        expect(persisted['grp-master']).toBe(true);
        expect(document.getElementById('grp-master').classList.contains('open')).toBe(true);
    });

    test('6. groups without saved state remain at server default', () => {
        createGroup('grp-pembelian', false);
        let state = loadState(); // empty
        restoreState(state);

        expect(document.getElementById('grp-pembelian').classList.contains('open')).toBe(false);
    });

    test('7. non-existent group ID in toggleGroup does not throw', () => {
        let state = {};
        expect(() => toggleGroup('grp-nonexistent', state)).not.toThrow();
    });

    test('8. corrupted localStorage gracefully falls back to empty state', () => {
        localStorage.setItem(SIDEBAR_STATE_KEY, '{invalid_json}');
        let state = loadState();
        expect(state).toEqual({});
    });

    test('9. multiple groups persist independently', () => {
        createGroup('grp-a', false);
        createGroup('grp-b', false);
        let state = loadState();
        state = toggleGroup('grp-a', state); // open A
        saveState(state);

        const persisted = loadState();
        expect(persisted['grp-a']).toBe(true);
        expect(persisted['grp-b']).toBeUndefined(); // B untouched
    });

    test('10. cross-page persistence: state written in one load is read in next', () => {
        // Simulate page 1: user opens grp-sdm
        saveState({ 'grp-sdm': true });

        // Simulate page 2: restore
        createGroup('grp-sdm', false);
        let state = loadState();
        restoreState(state);

        expect(document.getElementById('grp-sdm').classList.contains('open')).toBe(true);
    });
});

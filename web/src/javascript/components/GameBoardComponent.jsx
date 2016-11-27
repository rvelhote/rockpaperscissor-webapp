/**
 * MIT License
 *
 * Copyright (c) 2016 Ricardo Velhote
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
// 'use strict';

import React from 'react';
import Player from './PlayerComponent';
import Stats from './StatsComponent';
import Gameset from './GamesetComponent';

class GameBoardComponent extends React.Component {
  constructor(props) {
    super(props);

    this.onPlayClick = this.onPlayClick.bind(this);

    this.state = {
      player: {
        move: '',
        handle: '@rvelhote'
      },
      gameset: {
        guid: '',
        games: []
      },
      game: {
        opponent: {
          uuid: '',
          handle: '',
          picture: ''
        },
        moves: []
      },
      result: {
        move: '',
        winner: ''
      },
      stats: {
        win: 0,
        lose: 0,
        draw: 0
      },
      history: [],
      working: false
    };
  }

  componentDidMount() {
    this.requestNewGame().then(() => console.log(this.state));
  }

  onPlayClick(gamesetGuid, gameGuid, move) {
    // console.log(gamesetGuid, gameGuid, move);
    const data = new FormData();
    data.append('make_move_form[move]', move);
    data.append('make_move_form[game]', gameGuid);
    data.append('make_move_form[gameset]', gamesetGuid);

    const headers = new Headers();
    headers.append('Authorization', 'Bearer ' + window.localStorage.getItem('token'));

    // this.setState({working: true});
    //
    const player = {
      handle: this.state.player.handle,
      move: move
    };

    const request = new Request('http://localhost/api/v1/play', {
      method: 'POST',
      body: data,
      headers: headers
    });

    return fetch(request)
      .then(response => response.json())
      .then(
        response => this.setState({ working: false, player: player, stats: response.stats, gameset: response.gameset }));
  }

  requestNewGame() {
    // this.setState({working: true});

    const headers = new Headers();
    headers.append('Authorization', 'Bearer ' + window.localStorage.getItem('token'));

    const request = new Request('http://localhost/api/v1/game', {
      method: 'GET',
      headers: headers
    });
    return fetch(request).then(response => response.json()).then(
      response => this.setState({ working: false, stats: response.stats, gameset: response.gameset }));
  }

  login() {
    const headers = new Headers();
    headers.append('Authorization', 'Bearer ' + window.localStorage.getItem('token'));

    const data = new FormData();
    data.append('_username', '@rvelhote');
    data.append('_password', 'x');

    const request = new Request('http://localhost/api/v1/login', {
      method: 'POST',
      body: data,
      headers: headers
    });

    return fetch(request).then(response => response.json()).then(response => {
      window.localStorage.setItem('token', response.token);
    });
  }

  logout() {
    window.localStorage.removeItem('token');
  }

  /**
   *
   * @returns {XML}
   */
  render() {
    const working = this.state.working ? 'working' : '';

    return (
      <div className="row expanded">
        <header className="small-12 columns main">
          <div className="row collapse expanded">
            <div className="columns">
              <Player player={this.state.player}/>
            </div>

            <div className="columns">
              <button className="button button__login" onClick={this.login}>Login</button>
              <button className="button button__logout" onClick={this.logout}>Logout</button>
            </div>
          </div>
        </header>

        <section className="small-12 columns">
          <Gameset gameset={this.state.gameset} onPlayClick={this.onPlayClick} />
        </section>

        <footer className="small-12 columns">
          <div className="columns">
            <Stats win={this.state.stats.wins} lose={this.state.stats.losses} draw={this.state.stats.draws} />
          </div>
        </footer>
      </div>
    );
  }
}

GameBoardComponent.displayName = 'GameBoardComponent';

// Uncomment properties you need
// GameComponent.propTypes = {};
// GameComponent.defaultProps = {};

export default GameBoardComponent;

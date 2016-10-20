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
import Move from './MoveComponent';
import Stats from './StatsComponent';

import '../styles/Game.css';

class GameComponent extends React.Component {
    constructor(props) {
        super(props);

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
        this.requestNewGame();//.then(() => console.log(this.state));
    }

    onPlayClick(gamesetGuid, gameGuid, move) {
        // console.log(gamesetGuid, gameGuid, move);
        var data = new FormData();
        data.append('form[move]', move);
        data.append('form[game]', gameGuid);
        data.append('form[gameset]', gamesetGuid);

        var headers = new Headers();
        headers.append('Authorization', 'Bearer ' + window.localStorage.getItem('token'));

        // this.setState({working: true});
        //
        var player = {
            handle: this.state.player.handle,
            move: move
        };

        var request = new Request('http://localhost/api/v1/play', {
            method: 'POST',
            body: data,
            headers: headers
        });

        return fetch(request)
            .then(response => response.json())
            .then(response => this.setState({ working: false, player: player, stats: response.stats, result: response.result, game: response.game }));
    }

    requestNewGame() {
        // this.setState({working: true});

        var headers = new Headers();
        headers.append('Authorization', 'Bearer ' + window.localStorage.getItem('token'));

        var request = new Request('http://localhost/api/v1/game', {
            method: 'GET',
            headers: headers
        });
        return fetch(request).then(response => response.json()).then(response => this.setState({ working: false, stats: response.stats, gameset: response.gameset }));
    }

    login() {
        var headers = new Headers();
        headers.append('Authorization', 'Bearer ' + window.localStorage.getItem('token'));

        var data = new FormData();
        data.append('_username', '@rvelhote');
        data.append('_password', 'x');

        var request = new Request('http://localhost/api/v1/login', {
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
        var working;
        if(this.state.working) {
            working = 'working'
        }


        return (
            <section>
                <button onClick={this.login}>Login</button>
                <button onClick={this.logout}>Logout</button>

                <hr />

                __ {working} __

                <hr />

                <div>
                    <Stats win={this.state.stats.win} lose={this.state.stats.lose} draw={this.state.stats.draw}/>
                </div>
                <div>
                    <div>
                        <Player player={this.state.player} />

                        {
                            this.state.gameset.games.map((g) =>
                                <div key={g.guid}>
                                    <div>GameGUID: {g.guid}</div>


                                        <div>GameName: {g.game_type.name}</div>
                                        <div>GameName: {g.player2.username}</div>
                                    {
                                        g.game_type.move_types.map((m) => {
                                            return <Move key={m.slug}
                                                         disabled={this.state.working}
                                                         gameset={this.state.gameset.guid}
                                                         game={g.guid}
                                                         name={m.slug}
                                                         onPlayClick={this.onPlayClick.bind(this)} />
                                        })
                                    }



                                </div>
                            )
                        }
                    </div>

                    <div>
                        <span>vs</span>
                    </div>

                    <div>
                        <Player player={this.state.game.opponent} />
                    </div>
                </div>
                <div>
                    <div>
                        GamesetID: {this.state.gameset.guid}
                    </div>
                    <div>
                        <ul>
                            <li>Opponent played: {this.state.result.move}</li>
                            <li>You played: {this.state.player.move}</li>
                            <li>Result: {this.state.result.outcome}</li>
                            <li>Result: {this.state.result.winner}</li>
                            <li>Game ID: {this.state.game.guid}</li>
                        </ul>
                    </div>
                </div>
            </section>
        );
    }
}

GameComponent.displayName = 'GameComponent';

// Uncomment properties you need
// GameComponent.propTypes = {};
// GameComponent.defaultProps = {};

export default GameComponent;

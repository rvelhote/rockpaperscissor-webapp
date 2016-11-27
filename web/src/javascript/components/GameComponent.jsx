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
import React from 'react';

import MoveCollection from './MoveCollectionComponent';
import Stats from './StatsComponent';
import Player from './PlayerComponent';

const GameComponent = props => (
  <div className="columns">
    <article className="game" data-played={props.game.date_played !== null}>
      <header className="game__header">
        Playing&nbsp;
        <strong className="game__header game__game-type-name">{props.game.game_type.name}</strong>&nbsp;vs&nbsp;
        <Player player={props.game.player2} />
      </header>
      <main className="game__main">
        <MoveCollection onPlayClick={props.onPlayClick} gameset={props.gameset} game={props.game.guid} moves={props.game.game_type.move_types} />

        <ul>
          <li>You Played: {props.game.move_player1 != null ? props.game.move_player1.name : ''}</li>
          <li>Opponent Played: {props.game.move_player2 != null ? props.game.move_player2.name : ''}</li>
          <li data-result={props.game.result === 0}>It's a draw!</li>
          <li data-result={props.game.result === 1}>You Win!</li>
          <li data-result={props.game.result === 2}><strong>{props.game.player2.username}</strong> Wins!</li>
        </ul>
      </main>
      {/*<footer className="game__footer">*/}
        {/*<Stats win={-1} lose={-1} draw={-1} />*/}
      {/*</footer>*/}
    </article>
  </div>
);


GameComponent.displayName = 'GameComponent';

GameComponent.propTypes = {
  onPlayClick: React.PropTypes.func
};

GameComponent.defaultProps = {};

export default GameComponent;

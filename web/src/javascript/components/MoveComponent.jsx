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

const MoveComponent = props => (
  <li className="move-collection__move">
    <button
      data-move-player1={props.movePlayer1 !== null && props.name === props.movePlayer1.slug}
      data-move-player2={props.movePlayer2 !== null && props.name === props.movePlayer2.slug}
      className="move-collection__move__button"
      type="button"
      onClick={() => props.onPlayClick(props.gameset, props.game, props.name)}>
      <img width="85px" height="113px" alt={props.name} src={`/images/${props.name}.svg`} />
    </button>
  </li>
);

MoveComponent.displayName = 'MoveComponent';

MoveComponent.propTypes = {
  disabled: React.PropTypes.bool,
  name: React.PropTypes.string,
  gameset: React.PropTypes.string,
  game: React.PropTypes.string,
  onPlayClick: React.PropTypes.func
};

MoveComponent.defaultProps = {};

export default MoveComponent;


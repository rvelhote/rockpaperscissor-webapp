'use strict';

import React from 'react';

require('styles//Stats.css');

let StatsComponent = (props) => (
  <div className="stats">
    W: {props.win}<br/>
      L: {props.lose}<br/>
      D: {props.draw}<br/>
  </div>
);

StatsComponent.displayName = 'StatsComponent';

// Uncomment properties you need
// StatsComponent.propTypes = {};
// StatsComponent.defaultProps = {};

export default StatsComponent;

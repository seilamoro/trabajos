import React, {Component} from 'react';
import  '../css/ShoppingCart.css';

export default class ShoppingCart extends Component{
  render(){
    return(
      <div className="shoppingCart">
        <p><strong>Nombre:</strong>{this.props.article.Name}<strong>Precio:</strong>{this.props.article.Price}</p>
</div>
    );
  }
}

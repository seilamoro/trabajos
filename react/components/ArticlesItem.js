import React, {Component} from 'react';
import  '../css/ArticlesItem.css';

//<button onClick={this.handleClick.bind(this, this.props.code)}>Comprar</button>
export default class ArticlesItem extends Component{

  render(){
    return(
      <div className="articlesItem">

      <img src={require(this.props.article.Image)} />

        <p><strong>Nombre:</strong>{this.props.article.Name}</p>
        <p><strong>Precio:</strong>{this.props.article.Price}</p>

        <button onClick={this.props.addNewItem}>Comprar</button>
</div>
    );
  }
}

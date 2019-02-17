import React, {Component} from 'react';
import products from '../data/Products.json';
import  '../css/Articles.css';
import ArticlesItem from './ArticlesItem.js';

export default class Articles extends Component{
  render(){
    return(
      <div className="articles">
      {
        products.map(Article => {
          return <ArticlesItem
        //**  key={Article.Code}
        key={Article}
          article={Article}
          addNewItem={()=>this.props.addNewItem(Article)}
          />;
        })
      }
      </div>
    );
  }
}

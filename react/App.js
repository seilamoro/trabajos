import React, { Component } from 'react';
//import logo from './logo.svg';
import Articles from './components/Articles';
import ShoppingCart from './components/ShoppingCart.js'
import './App.css';

class App extends Component {
  constructor(props) {
    super(props);
    this.state={
      items:[],
      totalPrice:0.00
    };
  }

  render() {
    return (
      <div className="App">
      
        <Articles
          addNewItem={(ArticlesItem)=>this.addNewItem(ArticlesItem)}
        />

        <div className="articles">
        {
        this.state.items.map(Article => {
         return <ShoppingCart
          //**  key={Article.Code}
          key={Article}
          article={Article}
          />;

        })
      }
      </div>

      <div><strong>Total: </strong>{this.state.totalPrice}€</div>
      </div>
    );
  }

addNewItem(ArticlesItem){
  this.setState({items:this.state.items});
  this.state.items.push(ArticlesItem);

  this.calcularPrecioTotal();
}
calcularPrecioTotal(){
  var typeItems = [];

  this.state.items.forEach(function(currentItem) {
    var enc = false;

    typeItems.forEach(function(currentType) {
      if(currentItem.Code===currentType.Code){
        enc=true;
        currentType.NumSelect=parseInt(currentType.NumSelect)+1;
      }
    });

    if(!enc){
      currentItem.NumSelect="1";
      typeItems.push(currentItem);
    }
  });

  var totalPrice = 0.00;
  typeItems.forEach(function(currentType) {
    var itemPrice = parseFloat(currentType.Price);
    var itemNum = parseInt(currentType.NumSelect);

    //Aplicar descuentos, si el tipo de item tiene alguno
    if(currentType.PromoCode==="BulkPurchase" && parseInt(currentType.Min)<=itemNum){
      itemPrice=parseFloat(currentType.PromoPrice);
    }
    else if(currentType.PromoCode==="AxB" && parseInt(currentType.Min)<=itemNum){
      itemNum=Math.round((itemNum/parseInt(currentType.Min)))*parseInt(currentType.Free);
    }

    totalPrice=totalPrice+(itemNum*itemPrice);
  });
  totalPrice=parseFloat(totalPrice).toFixed(2);
  this.setState({totalPrice:totalPrice});

  this.updateCarrito(totalPrice);
}
updateCarrito(totalPrice){






}
  /*addNewItem(ArticlesItem){
    //var itemsAux=this.state.items;
    //itemsAux.push(ArticlesItem.Code);

    //Añadir el producto a la lista
  //**  this.setState({items:[...this.state.items, ArticlesItem.Code]});
this.setState({items:[...this.state.items, ArticlesItem]});
alert(this.state.items[0].Name);

    //Indicar que se añade un producto más de ese tipo
    var itemsTypeAux = this.state.itemsType;
    var enc=false;


    for(let i=0; i<itemsTypeAux.lenght; i++){
      if(ArticlesItem.Code===itemsTypeAux[i]){
        ArticlesItem.Num=itemsTypeAux.Num+1;
        enc=true;
      }
    }
    if(!enc){ //Si el tipo no estaba en el listado, se añade
      itemsTypeAux.push(ArticlesItem);
      ArticlesItem=1;
    }

    //Calcular el precio total
    var totalPrice = 0;
    alert(itemsTypeAux.lenght);
    for(let i=0; i<itemsTypeAux.lenght; i++){
      var currentItemType = itemsTypeAux[i];
      var itemPrice = currentItemType.Price;
      var itemNum = currentItemType.Num;

      //Aplicar descuentos, si el tipo de item tiene alguno
      if(currentItemType.PromoCode==="BulkPurchase" && currentItemType.Min<=itemNum){
        itemPrice=currentItemType.PromoPrice;
      }
      else if(currentItemType.PromoCode==="AxB"){
        itemNum=(itemNum/currentItemType.Min)*currentItemType.Free;
      }

      totalPrice=totalPrice+(itemNum*itemPrice);
    }

    alert(totalPrice);
  }
  addCarrito(){
    var itemsTypeAux = this.state.itemsType;
    alert(itemsTypeAux.lenght);
  }*/
}

export default App;

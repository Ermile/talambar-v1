var FileView=React.createClass({displayName:"FileView",render:function(){return React.createElement("div",{"class":"file"},this.props.name)}}),FolderView=React.createClass({displayName:"FolderView",peekInside:function(){},render:function(){return React.createElement("div",{"class":"folder",onClick:this.peekInside},this.props.name)}}),FileList=React.createClass({displayName:"FileList",createItems:function(){var items=this.props.items.map(function(item){return"folder"===item.type?React.createElement(FolderView,React.__spread({},item)):React.createElement(FileView,React.__spread({},item))});return items.length?items:null},render:function(){return React.createElement("ul",null,this.createItems()||"No Files Found")}});
//# sourceMappingURL=views.js.map
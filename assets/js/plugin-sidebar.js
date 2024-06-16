/*! For license information please see plugin-sidebar.js.LICENSE.txt */
!function(){var e={694:function(e,t,a){"use strict";var i=a(925);function l(){}function r(){}r.resetWarningCache=l,e.exports=function(){function e(e,t,a,l,r,o){if(o!==i){var n=new Error("Calling PropTypes validators directly is not supported by the `prop-types` package. Use PropTypes.checkPropTypes() to call them. Read more at http://fb.me/use-check-prop-types");throw n.name="Invariant Violation",n}}function t(){return e}e.isRequired=e;var a={array:e,bigint:e,bool:e,func:e,number:e,object:e,string:e,symbol:e,any:e,arrayOf:t,element:e,elementType:e,instanceOf:t,node:e,objectOf:t,oneOf:t,oneOfType:t,shape:t,exact:t,checkPropTypes:r,resetWarningCache:l};return a.PropTypes=a,a}},556:function(e,t,a){e.exports=a(694)()},925:function(e){"use strict";e.exports="SECRET_DO_NOT_PASS_THIS_OR_YOU_WILL_BE_FIRED"},20:function(e,t,a){"use strict";var i=a(594),l=Symbol.for("react.element"),r=(Symbol.for("react.fragment"),Object.prototype.hasOwnProperty),o=i.__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED.ReactCurrentOwner,n={key:!0,ref:!0,__self:!0,__source:!0};function s(e,t,a){var i,s={},c=null,d=null;for(i in void 0!==a&&(c=""+a),void 0!==t.key&&(c=""+t.key),void 0!==t.ref&&(d=t.ref),t)r.call(t,i)&&!n.hasOwnProperty(i)&&(s[i]=t[i]);if(e&&e.defaultProps)for(i in t=e.defaultProps)void 0===s[i]&&(s[i]=t[i]);return{$$typeof:l,type:e,key:c,ref:d,props:s,_owner:o.current}}t.jsx=s,t.jsxs=s},848:function(e,t,a){"use strict";e.exports=a(20)},594:function(e){"use strict";e.exports=React}},t={};function a(i){var l=t[i];if(void 0!==l)return l.exports;var r=t[i]={exports:{}};return e[i](r,r.exports,a),r.exports}a.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return a.d(t,{a:t}),t},a.d=function(e,t){for(var i in t)a.o(t,i)&&!a.o(e,i)&&Object.defineProperty(e,i,{enumerable:!0,get:t[i]})},a.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},function(){"use strict";var e=wp.plugins,t=wp.editPost,i=wp.components,l=wp.data,r=wp.compose,o=a(848),n=(0,r.compose)((0,l.withDispatch)(((e,t)=>({setMetaValue:a=>{e("core/editor").editPost({meta:{[t.metaKey]:a}})}}))),(0,l.withSelect)(((e,t)=>({metaValue:e("core/editor").getEditedPostAttribute("meta")[t.metaKey]}))))((e=>(0,o.jsx)(i.CheckboxControl,{label:e.label,checked:e.metaValue,onChange:t=>{e.setMetaValue(t)}}))),s=a(556),c=a.n(s);const d=(0,r.compose)((0,l.withDispatch)(((e,t)=>({setMetaValue:a=>{e("core/editor").editPost({meta:{[t.metaKey]:JSON.stringify(a)}})}}))),(0,l.withSelect)(((e,t)=>{const a=e("core/editor").getEditedPostAttribute("meta")[t.metaKey];return{metaValue:a?JSON.parse(a):[]}})))((e=>{const t=Object.entries(e.options).map((e=>{let[t,a]=e;return{value:t,label:a}}));return(0,o.jsxs)("div",{children:[e.label&&(0,o.jsx)("label",{children:e.label}),t.map((t=>(0,o.jsx)(i.CheckboxControl,{label:t.label,checked:e.metaValue.includes(t.value),onChange:()=>(t=>{const a=e.metaValue.includes(t)?e.metaValue.filter((e=>e!==t)):[...e.metaValue,t];e.setMetaValue(a)})(t.value)},t.value)))]})}));d.propTypes={label:c().string,options:c().objectOf(c().string).isRequired,metaKey:c().string.isRequired,metaValue:c().array.isRequired,setMetaValue:c().func.isRequired},d.defaultProps={options:{}};var u=d,m=wp.element;const p=e=>{let{value:t,onChange:a}=e;const l=(0,m.useRef)();return(0,m.useEffect)((()=>{if(window.tinymce)return tinymce.init({target:l.current,setup:e=>{e.on("init",(()=>{e.setContent(t||"")})),e.on("change keyup setcontent",(()=>{a(e.getContent())}))},menubar:!1,toolbar:"formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent | link",plugins:"advlist link image lists",block_formats:"Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6"}),()=>{window.tinymce&&tinymce.remove(l.current)}}),[t]),(0,o.jsxs)("div",{children:[!window.tinymce&&(0,o.jsx)(i.Spinner,{}),(0,o.jsx)("textarea",{ref:l})]})};var h=(0,r.compose)((0,l.withDispatch)(((e,t)=>({setMetaValue:a=>{console.log("Setting meta value:",a),e("core/editor").editPost({meta:{[t.metaKey]:a}})}}))),(0,l.withSelect)(((e,t)=>{const a=e("core/editor").getEditedPostAttribute("meta"),i=a?a[t.metaKey]:"";return console.log("Retrieved meta value:",i),{metaValue:i||""}})))((e=>(0,o.jsxs)("div",{children:[e.label&&(0,o.jsx)("label",{children:e.label}),(0,o.jsx)(p,{value:e.metaValue,onChange:t=>e.setMetaValue(t)})]}))),y=wp.blockEditor,x=wp.i18n,b=(0,r.compose)((0,l.withDispatch)(((e,t)=>({setMetaValue:a=>{e("core/editor").editPost({meta:{[t.metaKey]:a}})}}))),(0,l.withSelect)(((e,t)=>({metaValue:e("core/editor").getEditedPostAttribute("meta")[t.metaKey]}))))((e=>{const t=e.metaValue?JSON.parse(e.metaValue):null;return(0,o.jsxs)("div",{children:[e.label&&(0,o.jsx)("label",{children:e.label}),(0,o.jsx)(y.MediaUploadCheck,{children:(0,o.jsx)(y.MediaUpload,{onSelect:t=>{const a={id:t.id,url:t.url,name:t.title,type:t.mime};e.setMetaValue(JSON.stringify(a))},allowedTypes:e.allowedTypes,value:t?t.id:"",render:e=>{let{open:a}=e;return(0,o.jsx)(i.Button,{variant:"primary",onClick:a,children:t?(0,x.__)("Change File"):(0,x.__)("Upload File")})}})}),t&&(0,o.jsxs)("div",{children:[(0,o.jsx)("p",{children:(0,x.__)("File:","text-domain")}),(0,o.jsxs)("p",{children:[(0,x.__)("ID:","text-domain")," ",t.id]}),(0,o.jsxs)("p",{children:[(0,x.__)("URL:","text-domain")," ",(0,o.jsx)("a",{href:t.url,target:"_blank",rel:"noopener noreferrer",children:t.url})]}),(0,o.jsxs)("p",{children:[(0,x.__)("Name:","text-domain")," ",t.name]}),(0,o.jsxs)("p",{children:[(0,x.__)("Type:","text-domain")," ",t.type]})]})]})})),f=(0,r.compose)((0,l.withDispatch)(((e,t)=>({setMetaValue:a=>{const i=JSON.stringify(a);e("core/editor").editPost({meta:{[t.metaKey]:i}})}}))),(0,l.withSelect)(((e,t)=>{let a=e("core/editor").getEditedPostAttribute("meta")[t.metaKey],i=[];try{i=a?JSON.parse(a):[],Array.isArray(i)||(i=[])}catch(e){console.error("Failed to parse metaValue",e),i=[]}return{metaValue:i}})))((e=>(0,o.jsxs)("div",{children:[e.label&&(0,o.jsx)("label",{children:e.label}),(0,o.jsx)(y.MediaUploadCheck,{children:(0,o.jsx)(y.MediaUpload,{onSelect:t=>{const a={id:t.id,url:t.url,name:t.title,alt:t.alt},i=[...e.metaValue,a];e.setMetaValue(i)},allowedTypes:["image"],value:e.metaValue.map((e=>e.id)),render:e=>{let{open:t}=e;return(0,o.jsx)(i.Button,{onClick:t,children:(0,x.__)("Upload Image")})}})}),e.metaValue.length>0&&(0,o.jsx)("div",{children:e.metaValue.map((t=>(0,o.jsxs)("div",{children:[(0,o.jsx)("img",{src:t.url,alt:t.alt||(0,x.__)("Selected Image"),style:{maxWidth:"100%"}}),(0,o.jsxs)("p",{children:[(0,x.__)("Image ID:","text-domain")," ",t.id]}),(0,o.jsxs)("p",{children:[(0,x.__)("Image Name:","text-domain")," ",t.name]}),(0,o.jsxs)("p",{children:[(0,x.__)("Image Alt:","text-domain")," ",t.alt]}),(0,o.jsx)(i.Button,{onClick:()=>(t=>{const a=e.metaValue.filter((e=>e.id!==t));e.setMetaValue(a)})(t.id),children:(0,x.__)("Remove Image")})]},t.id)))})]}))),g=(0,r.compose)((0,l.withDispatch)(((e,t)=>({setMetaValue:a=>{e("core/editor").editPost({meta:{[t.metaKey]:a}})}}))),(0,l.withSelect)(((e,t)=>({metaValue:e("core/editor").getEditedPostAttribute("meta")[t.metaKey]}))))((e=>{const t=Object.keys(e.options).map((t=>({label:e.options[t],value:t})));return(0,o.jsx)(i.RadioControl,{label:e.label,selected:e.metaValue,options:t,onChange:t=>{e.setMetaValue(t)}})})),j=(0,r.compose)((0,l.withDispatch)(((e,t)=>({setMetaValue:a=>{e("core/editor").editPost({meta:{[t.metaKey]:a}})}}))),(0,l.withSelect)(((e,t)=>({metaValue:e("core/editor").getEditedPostAttribute("meta")[t.metaKey]||[]}))))((e=>{const[t,a]=(0,m.useState)(e.metaValue);return(0,o.jsxs)("div",{children:[e.label&&(0,o.jsx)("label",{children:e.label}),t.map(((l,r)=>(0,o.jsxs)("div",{style:{marginBottom:"10px"},children:[(0,o.jsx)(i.TextControl,{value:l,onChange:i=>((i,l)=>{const r=[...t];r[i]=l,a(r),e.setMetaValue(r)})(r,i)}),(0,o.jsx)(i.Button,{isDestructive:!0,onClick:()=>(i=>{const l=t.filter(((e,t)=>t!==i));a(l),e.setMetaValue(l)})(r),children:(0,x.__)("Remove","text-domain")})]},r))),(0,o.jsx)(i.Button,{variant:"isPrimary",onClick:()=>{const i=[...t,""];a(i),e.setMetaValue(i)},children:(0,x.__)("Add Item","text-domain")})]})})),V=(0,r.compose)((0,l.withDispatch)(((e,t)=>({setMetaValue:a=>{e("core/editor").editPost({meta:{[t.metaKey]:a}})}}))),(0,l.withSelect)(((e,t)=>({metaValue:e("core/editor").getEditedPostAttribute("meta")[t.metaKey]}))))((e=>{const t=Object.keys(e.options).map((t=>({label:e.options[t],value:t})));return(0,o.jsx)(i.SelectControl,{label:e.label,value:e.metaValue,options:t,onChange:t=>{e.setMetaValue(t)}})})),_=(0,r.compose)((0,l.withDispatch)(((e,t)=>({setMetaValue:a=>{e("core/editor").editPost({meta:{[t.metaKey]:a}})}}))),(0,l.withSelect)(((e,t)=>({metaValue:e("core/editor").getEditedPostAttribute("meta")[t.metaKey]}))))((e=>(0,o.jsx)(i.TextControl,{type:"text",label:e.label,value:e.metaValue,onChange:t=>{e.setMetaValue(t)}}))),v=(0,r.compose)((0,l.withDispatch)(((e,t)=>({setMetaValue:a=>{e("core/editor").editPost({meta:{[t.metaKey]:a}})}}))),(0,l.withSelect)(((e,t)=>({metaValue:e("core/editor").getEditedPostAttribute("meta")[t.metaKey]}))))((e=>(0,o.jsx)(i.TextareaControl,{label:e.label,value:e.metaValue,onChange:t=>{e.setMetaValue(t)}}))),w=(0,r.compose)((0,l.withDispatch)(((e,t)=>({setMetaValue:a=>{e("core/editor").editPost({meta:{[t.metaKey]:a}})}}))),(0,l.withSelect)(((e,t)=>({metaValue:e("core/editor").getEditedPostAttribute("meta")[t.metaKey]}))))((e=>(0,o.jsx)(i.TextControl,{type:"number",label:e.label,value:e.metaValue,onChange:t=>{e.setMetaValue(t)}})));const P=window.fields||[];console.log(P),(0,e.registerPlugin)("spf-sidebar",{render:()=>(0,o.jsx)(t.PluginSidebar,{name:"spf-sidebar",title:"SPF Sidebar",children:(0,o.jsx)(i.PanelBody,{children:P.map((e=>{switch(e.type){case"checkbox":return(0,o.jsx)(n,{label:e.name,metaKey:e.id},e.id);case"checkbox-list":return(0,o.jsx)(u,{label:e.name,metaKey:e.id,options:e.options},e.id);case"editor":return(0,o.jsx)(h,{label:e.name,metaKey:e.id},e.id);case"file":return(0,o.jsx)(b,{label:e.name,metaKey:e.id,allowedTypes:e.allowedTypes},e.id);case"image":return(0,o.jsx)(f,{label:e.name,metaKey:e.id,allowedTypes:e.allowedTypes},e.id);case"radio":return(0,o.jsx)(g,{label:e.name,metaKey:e.id,options:e.options},e.id);case"repeater":return(0,o.jsx)(j,{label:e.name,metaKey:e.id,fields:e.fields},e.id);case"select":return(0,o.jsx)(V,{label:e.name,metaKey:e.id,options:e.options},e.id);case"text":return(0,o.jsx)(_,{label:e.name,metaKey:e.id},e.id);case"textarea":return(0,o.jsx)(v,{label:e.name,metaKey:e.id},e.id);case"number":return(0,o.jsx)(w,{label:e.name,metaKey:e.id},e.id);default:return null}}))})})})}()}();
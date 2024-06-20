/*! For license information please see plugin-sidebar.js.LICENSE.txt */
!function(){var e={694:function(e,t,a){"use strict";var l=a(925);function n(){}function o(){}o.resetWarningCache=n,e.exports=function(){function e(e,t,a,n,o,r){if(r!==l){var i=new Error("Calling PropTypes validators directly is not supported by the `prop-types` package. Use PropTypes.checkPropTypes() to call them. Read more at http://fb.me/use-check-prop-types");throw i.name="Invariant Violation",i}}function t(){return e}e.isRequired=e;var a={array:e,bigint:e,bool:e,func:e,number:e,object:e,string:e,symbol:e,any:e,arrayOf:t,element:e,elementType:e,instanceOf:t,node:e,objectOf:t,oneOf:t,oneOfType:t,shape:t,exact:t,checkPropTypes:o,resetWarningCache:n};return a.PropTypes=a,a}},556:function(e,t,a){e.exports=a(694)()},925:function(e){"use strict";e.exports="SECRET_DO_NOT_PASS_THIS_OR_YOU_WILL_BE_FIRED"},20:function(e,t,a){"use strict";var l=a(594),n=Symbol.for("react.element"),o=(Symbol.for("react.fragment"),Object.prototype.hasOwnProperty),r=l.__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED.ReactCurrentOwner,i={key:!0,ref:!0,__self:!0,__source:!0};function s(e,t,a){var l,s={},d=null,u=null;for(l in void 0!==a&&(d=""+a),void 0!==t.key&&(d=""+t.key),void 0!==t.ref&&(u=t.ref),t)o.call(t,l)&&!i.hasOwnProperty(l)&&(s[l]=t[l]);if(e&&e.defaultProps)for(l in t=e.defaultProps)void 0===s[l]&&(s[l]=t[l]);return{$$typeof:n,type:e,key:d,ref:u,props:s,_owner:r.current}}t.jsx=s,t.jsxs=s},848:function(e,t,a){"use strict";e.exports=a(20)},594:function(e){"use strict";e.exports=React}},t={};function a(l){var n=t[l];if(void 0!==n)return n.exports;var o=t[l]={exports:{}};return e[l](o,o.exports,a),o.exports}a.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return a.d(t,{a:t}),t},a.d=function(e,t){for(var l in t)a.o(t,l)&&!a.o(e,l)&&Object.defineProperty(e,l,{enumerable:!0,get:t[l]})},a.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},function(){"use strict";var e=wp.plugins,t=wp.editPost,l=wp.components,n=wp.data,o=wp.compose,r=wp.element,i=(e,t,a,l,n)=>{const[o,i]=(0,r.useState)((()=>void 0!==e.value?e.value:e.metaValue||e.default||""));return(0,r.useEffect)((()=>{""===o&&l&&void 0!==e.default&&i(e.default),n(!1)}),[l,o,e.default,n]),{value:o,handleChange:l=>{i(l),void 0!==e.onChange?e.onChange(l):(e=>{i(e),""===e?a():t(e)})(l)}}},s=a(848),d=(0,o.compose)((0,n.withDispatch)(((e,t)=>({setMetaValue:a=>{e("core/editor").editPost({meta:{[t.metaKey]:a}})},deleteMetaValue:()=>{e("core/editor").editPost({meta:{[t.metaKey]:null}})}}))),(0,n.withSelect)(((e,t)=>({metaValue:e("core/editor").getEditedPostAttribute("meta")[t.metaKey]||!1}))))((e=>{const[t,a]=(0,r.useState)(!0),{value:n,handleChange:o}=i(e,e.setMetaValue,e.deleteMetaValue,t,a);return(0,s.jsx)(l.CheckboxControl,{label:e.label,checked:n,onChange:o})})),u=a(556),c=a.n(u);const m=(0,o.compose)((0,n.withDispatch)(((e,t)=>({setMetaValue:a=>{e("core/editor").editPost({meta:{[t.metaKey]:JSON.stringify(a)}})},deleteMetaValue:()=>{e("core/editor").editPost({meta:{[t.metaKey]:null}})}}))),(0,n.withSelect)(((e,t)=>{const a=e("core/editor").getEditedPostAttribute("meta")[t.metaKey];return{metaValue:a?JSON.parse(a):[]}})))((e=>{const[t,a]=(0,r.useState)(!0),[n,o]=(0,r.useState)((()=>void 0!==e.value?e.value:e.metaValue||e.default||""));(0,r.useEffect)((()=>{""===n&&t&&void 0!==e.default&&o(e.default),a(!1)}),[t,n,e.default,a]);const i=Object.entries(e.options).map((e=>{let[t,a]=e;return{value:t,label:a}}));return(0,s.jsxs)("div",{children:[e.label&&(0,s.jsx)("label",{children:e.label}),i.map((t=>(0,s.jsx)(l.CheckboxControl,{label:t.label,checked:n.includes(t.value),onChange:()=>(t=>{const a=n||[],l=a.includes(t)?a.filter((e=>e!==t)):[...a,t];o(l),e.onChange?e.onChange(l):e.setMetaValue(l)})(t.value)},t.value)))]})}));m.propTypes={label:c().string,options:c().objectOf(c().string).isRequired,metaKey:c().string.isRequired,metaValue:c().array.isRequired,setMetaValue:c().func.isRequired},m.defaultProps={options:{}};var p=m;const h=e=>{let{value:t,onChange:a}=e;const n=(0,r.useRef)();return(0,r.useEffect)((()=>{if(window.tinymce)return tinymce.init({target:n.current,setup:e=>{e.on("init",(()=>{e.setContent(t||"")})),e.on("change keyup setcontent",(()=>{a(e.getContent())}))},menubar:!1,toolbar:"formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent | link",plugins:"link",block_formats:"Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6"}),()=>{window.tinymce&&tinymce.remove(n.current)}}),[t]),(0,s.jsxs)("div",{children:[!window.tinymce&&(0,s.jsx)(l.Spinner,{}),(0,s.jsx)("textarea",{ref:n})]})};var y=(0,o.compose)((0,n.withDispatch)(((e,t)=>({setMetaValue:a=>{e("core/editor").editPost({meta:{[t.metaKey]:a}})}}))),(0,n.withSelect)(((e,t)=>{const a=e("core/editor").getEditedPostAttribute("meta");return{metaValue:(a?a[t.metaKey]:"")||""}})))((e=>{const[t,a]=(0,r.useState)(!0),{value:l,handleChange:n}=i(e,e.setMetaValue,e.deleteMetaValue,t,a);return(0,s.jsxs)("div",{children:[e.label&&(0,s.jsx)("label",{children:e.label}),(0,s.jsx)(h,{value:l,onChange:n})]})})),x=wp.blockEditor,f=wp.i18n,b=(0,o.compose)((0,n.withDispatch)(((e,t)=>({setMetaValue:a=>{e("core/editor").editPost({meta:{[t.metaKey]:JSON.stringify(a)}})},deleteMetaValue:()=>{e("core/editor").editPost({meta:{[t.metaKey]:null}})}}))),(0,n.withSelect)(((e,t)=>{const a=e("core/editor").getEditedPostAttribute("meta")[t.metaKey];return{metaValue:a?JSON.parse(a):null}})))((e=>{const[t,a]=(0,r.useState)((()=>void 0!==e.value?e.value||null:e.metaValue?JSON.parse(e.metaValue):null));return(0,s.jsxs)("div",{children:[e.label&&(0,s.jsx)("label",{children:e.label}),(0,s.jsxs)(x.MediaUploadCheck,{children:[(0,s.jsx)(x.MediaUpload,{onSelect:t=>{const l={id:t.id,url:t.url,name:t.title,type:t.mime};a(l),void 0!==e.onChange?(console.log("props.onChange"),e.onChange(l)):e.setMetaValue(JSON.stringify(l))},allowedTypes:e.allowedTypes,value:t?t.id:"",render:e=>{let{open:a}=e;return(0,s.jsx)(l.Button,{variant:"primary",onClick:a,children:t?(0,f.__)("Change File"):(0,f.__)("Upload File")})}}),(0,s.jsx)(l.Button,{variant:"primary",onClick:()=>{a(null),e.deleteMetaValue()},children:"Delete File"})]}),t&&(0,s.jsxs)("div",{children:[(0,s.jsx)("p",{children:(0,f.__)("File:","text-domain")}),(0,s.jsxs)("p",{children:[(0,f.__)("ID:","text-domain")," ",t.id]}),(0,s.jsxs)("p",{children:[(0,f.__)("URL:","text-domain")," ",(0,s.jsx)("a",{href:t.url,target:"_blank",rel:"noopener noreferrer",children:t.url})]}),(0,s.jsxs)("p",{children:[(0,f.__)("Name:","text-domain")," ",t.name]}),(0,s.jsxs)("p",{children:[(0,f.__)("Type:","text-domain")," ",t.type]})]})]})})),g=(0,o.compose)((0,n.withDispatch)(((e,t)=>({setMetaValue:a=>{const l=a?JSON.stringify(a):null;console.log("Setting meta value:",a),console.log("Stringified meta value:",l),e("core/editor").editPost({meta:{[t.metaKey]:l}})},deleteMetaValue:()=>{console.log("Deleting meta value"),e("core/editor").editPost({meta:{[t.metaKey]:null}})}}))),(0,n.withSelect)(((e,t)=>{const a=e("core/editor").getEditedPostAttribute("meta")[t.metaKey];let l=null;try{l=a?JSON.parse(a):null,("object"!=typeof l||Array.isArray(l))&&(l=null)}catch(e){console.error("Failed to parse metaValue",e),l=null}return{metaValue:l}})))((e=>{const[t,a]=(0,r.useState)((()=>void 0!==e.value?e.value||null:e.metaValue));return(0,s.jsxs)("div",{children:[e.label&&(0,s.jsx)("label",{children:e.label}),(0,s.jsx)(x.MediaUploadCheck,{children:(0,s.jsx)(x.MediaUpload,{onSelect:t=>{const l={id:t.id,url:t.url,name:t.title,alt:t.alt};a(l),void 0!==e.onChange?e.onChange(l):e.setMetaValue(l)},allowedTypes:["image"],value:t?t.id:"",render:e=>{let{open:a}=e;return(0,s.jsx)(l.Button,{onClick:a,children:t?(0,f.__)("Replace Image"):(0,f.__)("Upload Image")})}})}),t&&(0,s.jsx)("div",{children:(0,s.jsxs)("div",{children:[(0,s.jsx)("img",{src:t.url,alt:t.alt||(0,f.__)("Selected Image"),style:{maxWidth:"100%"}}),(0,s.jsxs)("p",{children:[(0,f.__)("Image ID:","text-domain")," ",t.id]}),(0,s.jsxs)("p",{children:[(0,f.__)("Image Name:","text-domain")," ",t.name]}),(0,s.jsxs)("p",{children:[(0,f.__)("Image Alt:","text-domain")," ",t.alt]}),(0,s.jsx)(l.Button,{onClick:()=>{a(null),e.deleteMetaValue()},children:(0,f.__)("Remove Image")})]})})]})})),v=(0,o.compose)((0,n.withDispatch)(((e,t)=>({setMetaValue:a=>{e("core/editor").editPost({meta:{[t.metaKey]:a}})}}))),(0,n.withSelect)(((e,t)=>({metaValue:e("core/editor").getEditedPostAttribute("meta")[t.metaKey]}))))((e=>{const t=Object.keys(e.options).map((t=>({label:e.options[t],value:t})));return(0,s.jsx)(l.RadioControl,{label:e.label,selected:e.metaValue,options:t,onChange:t=>{e.setMetaValue(t)}})})),j=(0,o.compose)((0,n.withDispatch)(((e,t)=>({setMetaValue:a=>{e("core/editor").editPost({meta:{[t.metaKey]:a}})},deleteMetaValue:()=>{e("core/editor").editPost({meta:{[t.metaKey]:null}})}}))),(0,n.withSelect)(((e,t)=>({metaValue:e("core/editor").getEditedPostAttribute("meta")[t.metaKey]}))))((e=>{const t=Object.keys(e.options).map((t=>({label:e.options[t],value:t})));return(0,s.jsx)(l.SelectControl,{label:e.label,value:e.metaValue,options:t,onChange:t=>{e.setMetaValue(t)}})})),V=(0,o.compose)((0,n.withDispatch)(((e,t)=>({setMetaValue:a=>{e("core/editor").editPost({meta:{[t.metaKey]:a}})},deleteMetaValue:()=>{e("core/editor").editPost({meta:{[t.metaKey]:null}})}}))),(0,n.withSelect)(((e,t)=>({metaValue:e("core/editor").getEditedPostAttribute("meta")[t.metaKey]||""}))))((e=>{const[t,a]=(0,r.useState)(!0),{value:n,handleChange:o}=i(e,e.setMetaValue,e.deleteMetaValue,t,a);return(0,s.jsx)(l.TextControl,{type:"text",label:e.label,value:n,onChange:o})})),_=(0,o.compose)((0,n.withDispatch)(((e,t)=>({setMetaValue:a=>{e("core/editor").editPost({meta:{[t.metaKey]:a}})},deleteMetaValue:()=>{e("core/editor").editPost({meta:{[t.metaKey]:null}})}}))),(0,n.withSelect)(((e,t)=>({metaValue:e("core/editor").getEditedPostAttribute("meta")[t.metaKey]||""}))))((e=>{const[t,a]=(0,r.useState)(!0),{value:n,handleChange:o}=i(e,e.setMetaValue,e.deleteMetaValue,t,a);return(0,s.jsx)(l.TextareaControl,{label:e.label,value:n,onChange:o})})),w=(0,o.compose)((0,n.withDispatch)(((e,t)=>({setMetaValue:a=>{e("core/editor").editPost({meta:{[t.metaKey]:a}})}}))),(0,n.withSelect)(((e,t)=>({metaValue:e("core/editor").getEditedPostAttribute("meta")[t.metaKey]}))))((e=>(0,s.jsx)(l.TextControl,{type:"number",label:e.label,value:e.metaValue,onChange:t=>{e.setMetaValue(t)}}))),C=(0,o.compose)((0,n.withDispatch)(((e,t)=>({setMetaValue:a=>{e("core/editor").editPost({meta:{[t.metaKey]:JSON.stringify(a)}})}}))),(0,n.withSelect)(((e,t)=>{const a=e("core/editor").getEditedPostAttribute("meta")[t.metaKey]||"[]";return{metaValue:JSON.parse(a)}})))((e=>{const[t,a]=(0,r.useState)(e.metaValue);(0,r.useEffect)((()=>{a(e.metaValue)}),[e.metaValue]);return(0,s.jsxs)("div",{children:[e.label&&(0,s.jsx)("label",{children:e.label}),t.map(((n,o)=>((n,o)=>(0,s.jsxs)("div",{style:{marginBottom:"10px"},children:[e.fields.map((l=>{const r={key:`${e.metaKey}-${o}-${l.id}`,label:l.name,value:n[l.id]?n[l.id].value:"",onChange:n=>((l,n,o)=>{const r=[...t];r[l]||(r[l]={}),r[l][n]||(r[l][n]={value:""}),r[l][n].value=o,a(r),e.setMetaValue(r)})(o,l.id,n),...void 0!==l.default&&{default:l.default}};switch(l.type){case"checkbox":return(0,s.jsx)(d,{...r});case"checkbox-list":return(0,s.jsx)(p,{...r,options:l.options});case"editor":return(0,s.jsx)(y,{...r});case"file":return(0,s.jsx)(b,{...r,allowedTypes:l.allowedTypes});case"image":return(0,s.jsx)(g,{...r,allowedTypes:l.allowedTypes});case"radio":return(0,s.jsx)(v,{...r,options:l.options});case"select":return(0,s.jsx)(j,{...r,options:l.options});case"text":return(0,s.jsx)(V,{...r});case"textarea":return(0,s.jsx)(_,{...r});case"number":return(0,s.jsx)(w,{...r});default:return null}})),(0,s.jsx)(l.Button,{isDestructive:!0,onClick:()=>(l=>{const n=t.filter(((e,t)=>t!==l));a(n),e.setMetaValue(n)})(o),children:(0,f.__)("Remove","text-domain")})]},o))(n,o))),(0,s.jsx)(l.Button,{variant:"isPrimary",onClick:()=>{const l=e.fields.reduce(((e,t)=>(e[t.id]={type:t.type,value:t.default||""},e)),{}),n=[...t,l];a(n),e.setMetaValue(n)},children:(0,f.__)("Add Item","text-domain")})]})}));const S=window.fields||[];console.log(S),(0,e.registerPlugin)("spf-sidebar",{render:()=>(0,s.jsx)(t.PluginSidebar,{name:"spf-sidebar",title:"SPF Sidebar",children:(0,s.jsx)(l.PanelBody,{children:S.map((e=>{switch(e.type){case"checkbox":return(0,s.jsx)(d,{label:e.name,metaKey:e.id},e.id);case"checkbox-list":return(0,s.jsx)(p,{label:e.name,metaKey:e.id,options:e.options},e.id);case"editor":return(0,s.jsx)(y,{label:e.name,metaKey:e.id},e.id);case"file":return(0,s.jsx)(b,{label:e.name,metaKey:e.id,allowedTypes:e.allowedTypes},e.id);case"image":return(0,s.jsx)(g,{label:e.name,metaKey:e.id,allowedTypes:e.allowedTypes},e.id);case"radio":return(0,s.jsx)(v,{label:e.name,metaKey:e.id,options:e.options},e.id);case"repeater":return(0,s.jsx)(C,{label:e.name,metaKey:e.id,fields:e.fields},e.id);case"select":return(0,s.jsx)(j,{label:e.name,metaKey:e.id,options:e.options},e.id);case"text":return(0,s.jsx)(V,{label:e.name,metaKey:e.id,default:e.default},e.id);case"textarea":return(0,s.jsx)(_,{label:e.name,metaKey:e.id},e.id);case"number":return(0,s.jsx)(w,{label:e.name,metaKey:e.id},e.id);default:return null}}))})})})}()}();
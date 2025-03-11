import{B as s,o as l,c as d,a as o,n as t,p as r,s as p}from"./app-qDQxZF5W.js";var c=function(n){var a=n.dt;return`
.p-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    padding: `.concat(a("toolbar.padding"),`;
    background: `).concat(a("toolbar.background"),`;
    border: 1px solid `).concat(a("toolbar.border.color"),`;
    color: `).concat(a("toolbar.color"),`;
    border-radius: `).concat(a("toolbar.border.radius"),`;
    gap: `).concat(a("toolbar.gap"),`;
}

.p-toolbar-start,
.p-toolbar-center,
.p-toolbar-end {
    display: flex;
    align-items: center;
}
`)},i={root:"p-toolbar p-component",start:"p-toolbar-start",center:"p-toolbar-center",end:"p-toolbar-end"},b=s.extend({name:"toolbar",theme:c,classes:i}),m={name:"BaseToolbar",extends:p,props:{ariaLabelledby:{type:String,default:null}},style:b,provide:function(){return{$pcToolbar:this,$parentInstance:this}}},u={name:"Toolbar",extends:m,inheritAttrs:!1},v=["aria-labelledby"];function y(e,n,a,$,f,g){return l(),d("div",r({class:e.cx("root"),role:"toolbar","aria-labelledby":e.ariaLabelledby},e.ptmi("root")),[o("div",r({class:e.cx("start")},e.ptm("start")),[t(e.$slots,"start")],16),o("div",r({class:e.cx("center")},e.ptm("center")),[t(e.$slots,"center")],16),o("div",r({class:e.cx("end")},e.ptm("end")),[t(e.$slots,"end")],16)],16,v)}u.render=y;export{u as s};

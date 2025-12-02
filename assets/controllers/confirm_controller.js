import { Controller } from "@hotwired/stimulus";
import { Modal } from "bootstrap";

export default class extends Controller{
    open(e){
        e.preventDefault();
        const fid = e.currentTarget.dataset.confirmFormId;
        const f = document.getElementById(fid);
        if(!f) return;
        const bs = new Modal(document.getElementById('confirm-modal'));
        bs.show();
        document.getElementById('confirm-ok').addEventListener('click', ()=>{ f.submit(); bs.hide() }, { once:true });
    }
}

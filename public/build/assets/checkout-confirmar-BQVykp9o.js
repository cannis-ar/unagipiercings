document.addEventListener("DOMContentLoaded",()=>{window.addEventListener("carrito:updated",e=>{const{items:o,total:n}=e.detail;i(o),d(n)});const s=document.getElementById("checkout-items-list");s&&s.addEventListener("click",e=>{const o=e.target.closest("[data-action]");if(!o)return;const n=parseInt(o.dataset.proId,10),t=o.dataset.action,c=o.closest(".carrito-item__qty")?.querySelector(".carrito-qty-value"),a=c?parseInt(c.textContent,10):1;t==="dec"&&a>1&&window.Carrito.actualizar(n,a-1),t==="inc"&&window.Carrito.actualizar(n,a+1)});function r(e){return Number(e).toLocaleString("es-AR",{minimumFractionDigits:0})}function i(e){const o=document.getElementById("checkout-items-list"),n=document.getElementById("btn-continuar");if(o){if(!e||!e.length){o.innerHTML='<p style="color:var(--color-text-muted); font-size:.875rem; padding:1rem 0;">Tu carrito está vacío. <a href="/shop">Ver productos</a></p>',n&&(n.style.pointerEvents="none");return}n&&(n.style.pointerEvents=""),o.innerHTML=e.map(t=>{const c=t.proImagen?`/storage/${t.proImagen}`:"/images/placeholder.jpg",a=t.tieneDescuento?`<span class="checkout-item__badge">-${t.porcentajeDescuento}%</span>`:"",u=t.tieneDescuento?`<s>$${r(t.proPrecio)}</s> `:"";return`
                <div class="checkout-item" data-pro-id="${t.proID}">
                    <div class="checkout-item__img-wrap">
                        <img class="checkout-item__img" src="${c}" alt="${t.proNombre}" loading="lazy">
                        ${a}
                    </div>
                    <div class="checkout-item__info">
                        <span class="checkout-item__nombre">${t.proNombre}</span>
                        <span class="checkout-item__precio">${u}$${r(t.precioPagado)}</span>
                        <div class="checkout-item__controls">
                            <div class="carrito-item__qty" role="group">
                                <button class="carrito-qty-btn" data-action="dec" data-pro-id="${t.proID}" ${t.cantidad<=1?"disabled":""}>−</button>
                                <span class="carrito-qty-value">${t.cantidad}</span>
                                <button class="carrito-qty-btn" data-action="inc" data-pro-id="${t.proID}">+</button>
                            </div>
                            <span class="checkout-item__subtotal">$${r(t.subtotal)}</span>
                            <button class="checkout-item__remove" onclick="window.Carrito.quitar(${t.proID})" type="button">×</button>
                        </div>
                    </div>
                </div>`}).join("")}}function d(e){const o=c=>Number(c).toLocaleString("es-AR",{minimumFractionDigits:0}),n=document.getElementById("co-subtotal"),t=document.getElementById("co-total");n&&(n.textContent=`$${o(e)}`),t&&(t.textContent=`$${o(e)}`)}});

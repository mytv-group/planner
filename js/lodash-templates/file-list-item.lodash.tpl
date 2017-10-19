<script type="text/template" id="heap__file-list-item" class="lodash-template">
    <div class="heap__file-list-item"
        data-mime="<%-mime%>"
        data-link="<%-link%>"
        data-type="<%-type%>"
    >
        <div class="heap__file-list-item__num">
            <%-index%>.
        </div>
        <div class="heap__file-list-item__text">
            <%-text%>.
        </div>
        <div class="heap__file-list-item__tags">
            <%-tags%>
        </div>
        <div class="heap__file-list-item__duration">
            <%=parseInt(duration)%> sec
        </div>
        <div class="heap__file-list-item__size">
            <%=parseInt(size / 1000000)%> MB
        </div>
    </div>
</script>

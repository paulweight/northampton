<%@ Page Language="C#" AutoEventWireup="true" %>
<%
Response.Redirect("404.php?request=" + Regex.Replace(HttpContext.Current.Request.RawUrl.Substring(HttpContext.Current.Request.RawUrl.IndexOf("?404;") + 5), @"((http://|https://)([a-zA-Z0-9\-.]*)(:[0-9]*)?/)", ""));
%>
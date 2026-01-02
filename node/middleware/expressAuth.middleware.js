
export default class  AuthenticateExpressRoute {

     constructor(wsTokens, allowedProjects){
         this.wsTokens = wsTokens
         this.allowedProjects = allowedProjects
     }

     authenticateReq = async (req, res, next) => {
        let token = req.query.ws_token;
        let userId = req.query.user_id;
        let tokenIsValid = await this.wsTokens.isValid(token, userId);
        let canAccessProject = await this.allowedProjects.canAccessHostProject(userId, req.query.hostId, req.query.project)

        if(req.path === "/node/operations/.websocket"){
            // We dont use the project provided by the user in this route
            canAccessProject = true;
        }

        if (!tokenIsValid || !canAccessProject) {
            return next(new Error('authentication error'));
        }else{
            next();
        }
    }
}

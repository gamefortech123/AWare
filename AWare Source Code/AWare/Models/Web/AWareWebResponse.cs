using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace AWare.Models.Web
{
    public class AWareWebResponse
    {
        public bool success { get; set; } = false;
        public string SecretKey { get; set; } = null;
        public string EncryptionKey { get; set; } = null;
        public bool Ready { get; set; } = false;
        public string SessionID { get; set; }
    }
}
